<?php

namespace App\Helpers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

/**
 * Class Cart
 *
 * @package App\Helpers
 */
class Cart
{
    /**
     * Return quantity of items in cart.
     *
     * @return int
     */
    public static function getCartItemsCount(): int
    {
        $request = \request();
        $user    = $request->user();
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
     * Return array of cart items.
     *
     * @return mixed
     */
    public static function getCartItems()
    {
        $request = \request();
        $user    = $request->user();
        if ($user) {
            return CartItem::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }

    /**
     * Return items we have in cookies.
     *
     * @return mixed
     */
    public static function getCookieCartItems()
    {
        $request = \request();
        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    /**
     * Return count from cartItems.
     *
     * @param $cartItems
     *
     * @return int
     */
    public static function getCountFromItems($cartItems): int
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }

    /**
     * Move cart items into DB.
     *
     * @return void
     */
    public static function moveCartItemsIntoDb(): void
    {
        $request      = \request();
        $cartItems    = self::getCookieCartItems();
        $dbCartItems  = CartItem::where(['user_id' => $request->user()->id])->get()->keyBy(
            'product_id'
        );
        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }
            $newCartItems[] = [
                'user_id'    => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity'   => $cartItem['quantity'],
            ];
        }
        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

    public static function getProductsAndCartItems(): array|Collection
    {
        $cartItems = self::getCartItems();
        $ids       = Arr::pluck($cartItems, 'product_id');
        $products  = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');
        return [$products, $cartItems];
    }
}
