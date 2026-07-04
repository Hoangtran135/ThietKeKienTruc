<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order', 'max_discount',
        'usage_limit', 'used_count', 'is_active', 'expires_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'date',
    ];

    public function isValid(?int $subtotal = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($subtotal !== null && $this->min_order > 0 && $subtotal < $this->min_order) return false;
        return true;
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'percent'  => "Giảm {$this->value}%",
            'fixed'    => 'Giảm ' . number_format($this->value) . '₫',
            'freeship' => 'Miễn phí vận chuyển',
            default    => $this->type,
        };
    }

    public static function findValid(string $code, ?int $subtotal = null): ?self
    {
        $voucher = self::where('code', strtoupper(trim($code)))->first();
        return ($voucher && $voucher->isValid($subtotal)) ? $voucher : null;
    }
}
