<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function getOrdersForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Order::query()
            ->with('items.product')
            ->whereBelongsTo($user)
            ->latest()
            ->paginate($perPage);
    }

    public function findWithItems(int $orderId): Order
    {
        return Order::with('items.product')->findOrFail($orderId);
    }

    public function recentForDashboard(User $user): Collection
    {
        return Order::query()
            ->with('items.product')
            ->whereBelongsTo($user)
            ->latest()
            ->limit(5)
            ->get();
    }
}
