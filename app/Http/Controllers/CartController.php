<?php

namespace App\Http\Controllers;

use App\Helpers\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

/**
 * Class CartController manages cart-related actions and interactions.
 *
 * @package App\Http\Controllers
 */
class CartController extends Controller
{
    /**
     * Display the contents of the shopping cart.
     *
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        list($products, $cartItems) = Cart::getProductsAndCartItems();
        $total = 0;
        foreach ($products as $product) $total += $product->price * $cartItems[$product->id]['quantity'];

        return view('cart.index', compact('cartItems', 'products', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     *
     * @param Request $request The HTTP request.
     * @param Product $product The product to add.
     * @return Response The response indicating the result of the operation.
     */
    public function add(Request $request, Product $product): Response
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();
        if ($user) {

            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
            } else {
                $data = [
                    'user_id' => $request->user()->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];
                CartItem::create($data);
            }

            return response([
                'count' => Cart::getCartItemsCount()
            ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    /**
     * Remove a product from the shopping cart.
     *
     * @param Request $request The HTTP request.
     * @param Product $product The product to remove.
     * @return Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory The response indicating the result of the operation.
     */
    public function remove(Request $request, Product $product): Application|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) $cartItem->delete();

            return response(['count' => Cart::getCartItemsCount()]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }

    /**
     * Update the quantity of a product in the shopping cart.
     *
     * @param Request $request The HTTP request.
     * @param Product $product The product to update.
     * @return Response The response indicating the result of the operation.
     */
    public function updateQuantity(Request $request, Product $product): Response
    {
        $quantity = (int)$request->post('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $request->user()->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);

            return response(['count' => Cart::getCartItemsCount()]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }
}
