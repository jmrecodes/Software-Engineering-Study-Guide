<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdateProductInventory implements ShouldQueue
{
    public function handle(OrderPlaced $event): void
    {
        foreach ($event->order->items as $item) {
            $updated = Product::whereKey($item->product_id)
                ->where('stock', '>=', $item->quantity)
                ->decrement('stock', $item->quantity);

            if (!$updated) {
                Log::warning('Inventory adjustment skipped due to insufficient stock', [
                    'order_id' => $event->order->id,
                    'product_id' => $item->product_id,
                ]);
            }
        }
    }
}
