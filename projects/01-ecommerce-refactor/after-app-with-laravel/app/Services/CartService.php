<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function getItems(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function addProduct(Product $product, int $quantity): void
    {
        $cart = $this->getItems();
        $line = Arr::get($cart, $product->id, [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 0,
        ]);

        $line['quantity'] += $quantity;
        $line['subtotal'] = $line['price'] * $line['quantity'];

        $cart[$product->id] = $line;

        Session::put(self::SESSION_KEY, $cart);
    }

    public function updateQuantity(Product $product, int $quantity): void
    {
        $cart = $this->getItems();

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id]['quantity'] = $quantity;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['price'] * $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getTotals(): array
    {
        $subtotal = array_reduce($this->getItems(), function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $tax = round($subtotal * config('cart.tax_rate', 0.1), 2);
        $shipping = $subtotal > 100 ? 0 : (float) config('cart.default_shipping', 9.99);
        $total = round($subtotal + $tax + $shipping, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }
}
