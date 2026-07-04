<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderStatusEmailNotification
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order->load(['customer', 'details.product']);
        $customer = $order->customer;

        if (! $customer?->email) {
            return;
        }

        try {
            Mail::to($customer->email)->send(new OrderStatusChangedMail($order));
            Log::info("[Email] Đã gửi cập nhật trạng thái đơn #{$order->id} tới {$customer->email}");
        } catch (\Exception $e) {
            Log::error("[Email] Lỗi gửi email cập nhật đơn #{$order->id}: ".$e->getMessage());
        }
    }
}
