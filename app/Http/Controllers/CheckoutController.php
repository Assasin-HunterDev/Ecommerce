<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

/**
 * Class CheckoutController manages to handle the checkout process, payments, and order management.
 *
 * @package App\Http\Controllers
 */
class CheckoutController extends Controller
{
    /**
     * Process the checkout and initiate a Stripe session for payment.
     *
     * @param Request $request The HTTP request instance.
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     * @throws ApiErrorException
     */
    public function checkout(Request $request): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        /** @var User $user */
        $user = $request->user();
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        list($products, $cartItems) = Cart::getProductsAndCartItems();

        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->title,
                        'images' => [$product->image],
                    ],
                    'unit_amount' => intval(round($product->price, 2) * 100),
                ],
                'quantity' => $quantity,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }

        // Create Stripe checkout sessions
        $session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure'),
        ]);

        // Create Order
        $orderData = [
            'total_price' => $totalPrice,
            'status' => OrderStatus::Unpaid,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
        $order = Order::create($orderData);

        // Create Order Items
        foreach ($orderItems as $orderItem) {
            $orderItem['order_id'] = $order->id;
            OrderItem::create($orderItem);
        }

        // Create Payment
        $paymentData = [
            'order_id' => $order->id,
            'amount' => $totalPrice,
            'status' => PaymentStatus::Pending,
            'type' => 'cc',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'session_id' => $session->id
        ];
        Payment::create($paymentData);

        CartItem::query()->where(['user_id' => $user->id])->delete();

        return redirect($session->url);
    }

    /**
     * Handle the success response from Stripe after payment completion.
     *
     * @param Request $request The HTTP request instance.
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function success(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));

        try {
            $session_id = $request->get('session_id');
            $session = $stripe->checkout->sessions->retrieve($session_id);
            if (!$session) {
                return view('checkout.failure', ['message' => 'Invalid Session ID']);
            }

            $payment = Payment::query()
                ->where('session_id', $session_id)
                ->whereIn('status', [PaymentStatus::Pending, PaymentStatus::Paid])
                ->firstOrFail();
            $payment->status = PaymentStatus::Paid;
            $payment->update();

            $order = $payment->order;
            $order->status = OrderStatus::Paid;
            $order->update();

            if ($payment->status === PaymentStatus::Pending->value) $this->updateOrderAndSession($payment);

            $customer = $session->customer_details;

            return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Handle the failure response during the checkout process.
     *
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function failure(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('checkout.failure', ['message' => ""]);
    }

    /**
     * Process the checkout for a specific order and initiate a Stripe session for payment.
     *
     * @param Order $order The order instance to be processed.
     * @return Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
     * @throws ApiErrorException
     */
    public function checkoutOrder(Order $order): Application|Redirector|RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->title,
                        'images' => [$item->product->image]
                    ],
                    'unit_amount' => intval(round($item->unit_price, 2) * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Create Stripe checkout sessions
        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure'),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();

        return redirect($session->url);
    }

    /**
     * Handle incoming Stripe webhook events.
     *
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function webhook(): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $endpoint_secret = env('WEBHOOK_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $paymentIntent = $event->data->object;
                    $sessionId = $paymentIntent['id'];

                    $payment = Payment::query()
                        ->where('session_id', $sessionId)
                        ->where('status', PaymentStatus::Pending)
                        ->firstOrFail();

                        $this->updateOrderAndSession($payment);
                    break;
                default:
                    echo 'Received unknown event type ' . $event->type;
            }
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        return response('', 200);
    }

    /**
     * Update payment and order status after successful payment.
     *
     * @param Builder|Model $payment The payment instance to update.
     * @return void
     */
    private function updateOrderAndSession(Builder|Model $payment): void
    {
        $payment->status = PaymentStatus::Paid->value;
        $payment->update();

        $order = $payment->order;
        $order->status = OrderStatus::Paid->value;
        $order->update();
    }
}
