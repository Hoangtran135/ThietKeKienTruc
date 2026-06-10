<?php

namespace App\Models;

use App\Services\Payment\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'status', 'payment_method', 'payment_status',
        'shipping_method', 'shipping_fee', 'voucher_code', 'discount_amount',
    ];

    protected $casts = [
        'status'          => 'integer',
        'payment_status'  => 'integer',
        'shipping_fee'    => 'integer',
        'discount_amount' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 1 ? 'Đã giao' : 'Chờ xử lý';
    }

    public function getSubtotalAttribute(): float
    {
        return $this->details->sum(fn($d) => $d->price * $d->number);
    }

    public function getTotalAttribute(): float
    {
        return max(0, $this->subtotal + $this->shipping_fee - $this->discount_amount);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return PaymentMethodFactory::make($this->payment_method)->label();
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return $this->payment_status === 1 ? 'Đã thanh toán' : 'Chưa thanh toán';
    }

    public function getShippingMethodLabelAttribute(): string
    {
        return match ($this->shipping_method) {
            'express' => 'Giao hàng hỏa tốc',
            'free'    => 'Miễn phí vận chuyển',
            default   => 'Giao hàng tiêu chuẩn',
        };
    }
}
