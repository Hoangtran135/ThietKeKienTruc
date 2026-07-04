<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function allWithCustomer(?int $status = null, int $perPage = 25): LengthAwarePaginator
    {
        return Order::with('customer')
            ->when($status !== null, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    public function findWithDetails(int $id): Order
    {
        return Order::with(['customer', 'details.product'])->findOrFail($id);
    }
}
