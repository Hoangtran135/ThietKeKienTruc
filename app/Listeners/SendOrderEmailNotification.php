<?php

namespace App\Listeners;
use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

/**
 * Observer: gửi email xác nhận đơn hàng (demo - ghi log thay vì gửi mail thật).
 */
class SendOrderEmailNotification
{
    public function handle(OrderPlaced $event): void
    {
        $order    = $event->order;
        $customer = $order->customer;

        Log::info("[Email] Gửi email xác nhận đơn hàng #{$order->id} tới {$customer?->email}", [
            'order_id' => $order->id,
            'total'    => $order->total,
        ]);
    }
}
