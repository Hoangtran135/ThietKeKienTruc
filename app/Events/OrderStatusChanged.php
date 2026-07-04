<?php

namespace App\Events;

use App\Models\Order;

class OrderStatusChanged
{
    public function __construct(public Order $order, public int $oldStatus, public int $newStatus) {}
}
