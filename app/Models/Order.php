<?php

namespace App\Models;

use App\Services\Payment\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 0;

    const STATUS_CONFIRMED = 1;

    const STATUS_SHIPPING = 2;

    const STATUS_DELIVERED = 3;

    const STATUS_CANCELLED = 4;

    protected $fillable = [
        'customer_id', 'status', 'payment_method', 'payment_status',
        'shipping_method', 'shipping_fee', 'voucher_code', 'discount_amount', 'note',
    ];

    protected $casts = [
        'status' => 'integer',
        'payment_status' => 'integer',
        'shipping_fee' => 'integer',
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
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_SHIPPING => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao hàng',
            self::STATUS_CANCELLED => 'Đã huỷ',
            default => 'Chờ xử lý',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_SHIPPING => 'warning',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'default',
        };
    }

    public function isCancellable(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->details->sum(fn ($d) => $d->price * $d->number);
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
            'free' => 'Miễn phí vận chuyển',
            default => 'Giao hàng tiêu chuẩn',
        };
    }
}
