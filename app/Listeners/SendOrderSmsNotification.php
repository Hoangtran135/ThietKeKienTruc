<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class SendOrderSmsNotification
{
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;
        $customer = $order->customer;

        Log::info("[SMS] Gửi SMS xác nhận đơn hàng #{$order->id} tới số {$customer?->phone}", [
            'order_id' => $order->id,
        ]);
    }
}
