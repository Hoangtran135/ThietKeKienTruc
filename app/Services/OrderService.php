<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function deliver(int $orderId): void
    {
        Order::findOrFail($orderId)->update(['status' => 1]);
    }
}
