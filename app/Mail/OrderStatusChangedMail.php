<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    // Nhận thông tin đơn hàng cần gửi email
    public function __construct(public Order $order) {}

    // Thiết lập tiêu đề email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'MediaMart - Đơn hàng #' . $this->order->id .
                     ' đã cập nhật: ' . $this->order->status_label,
        );
    }

    // Xác định giao diện và dữ liệu của email
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-status-changed',
            with: ['order' => $this->order],
        );
    }

    // Không sử dụng tệp đính kèm
    public function attachments(): array
    {
        return [];
    }
}