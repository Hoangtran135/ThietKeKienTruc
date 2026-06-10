<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function allWithCustomer(int $perPage = 25): LengthAwarePaginator
    {
        return Order::with('customer')->latest()->paginate($perPage);
    }

    public function findWithDetails(int $id): Order
    {
        return Order::with(['customer', 'details.product'])->findOrFail($id);
    }
}
