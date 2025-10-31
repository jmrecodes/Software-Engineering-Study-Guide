<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly Dispatcher $events
    ) {
    }

    public function placeOrder(int $userId, string $shippingAddress, string $paymentMethod): Order
    {
        $cartItems = $this->cartService->getItems();

        if (empty($cartItems)) {
            throw new RuntimeException('Cannot place an order with an empty cart.');
        }

        $totals = $this->cartService->getTotals();

        /** @var Order $order */
        $order = DB::transaction(function () use ($userId, $shippingAddress, $paymentMethod, $cartItems, $totals) {
            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $totals['subtotal'],
                'tax' => $totals['tax'],
                'shipping' => $totals['shipping'],
                'total' => $totals['total'],
                'status' => 'processing',
                'shipping_address' => $shippingAddress,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new RuntimeException("Insufficient stock for {$product->name}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        $this->events->dispatch(new OrderPlaced($order->fresh('items.product')));
        $this->cartService->clear();

        return $order->load('items.product');
    }
}
