<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        /** @var User $user */
        $user = $request->user();
        $orders = Order::withCount('items')
            ->where(['created_by' => $user->id])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('order.index', compact('orders'));
    }

    public function view(Request $request, Order $order): Application|View|Factory|Response|\Illuminate\Contracts\Foundation\Application|ResponseFactory
    {
        /** @var User $user */
        $user = $request->user();

        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }

        return view('order.view', compact('order'));
    }
}
