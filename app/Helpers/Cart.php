<?php

namespace App\Helpers;

use App\Models\Api\Product;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

/**
 * A helper class for managing cart-related operations.
 *
 * @package App\Helpers
 */
class Cart
{
    /**
     * Get the total count of cart items for the current user.
     *
     * @return int
     */
    public static function getCartItemsCount(): int
    {
        $request = request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                0
            );
        }
    }

    /**
     * Get the cart items for the current user or from cookies.
     *
     * @return mixed
     */
    public static function getCartItems(): mixed
    {
        $request = request();
        $user = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }

    /**
     * Get cart items stored in cookies.
     *
     * @return mixed
     */
    public static function getCookieCartItems(): mixed
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
    }

    /**
     * Calculate the total count of items from an array of cart items.
     *
     * @param array $cartItems
     * @return int
     */
    public static function getCountFromItems(array $cartItems): int
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }

    /**
     * Move cart items from cookies into the database for the current user.
     *
     * @return void
     */
    public static function moveCartItemsIntoDb(): void
    {
        $request = request();
        $cartItems = self::getCookieCartItems();
        $dbCartItems = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy('product_id');
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) continue;
            $newCartItems[] = [
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
            ];
        }

        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

    /**
     * Get products and cart items.
     *
     * @return array|Collection An array containing a Collection of products and an array of cart items.
     */
    public static function getProductsAndCartItems(): array|Collection
    {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');

        return [$products, $cartItems];
    }
}
