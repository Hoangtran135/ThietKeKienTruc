<?php

namespace App\Support;

use App\Models\Voucher;

class SiteSettings
{
    private static ?SiteSettings $instance = null;

    private int $freeshipThreshold = 500000;

    private int $standardShippingFee = 30000;

    private int $expressShippingFee = 60000;

    private array $vouchers = [
        'GIAM10' => ['type' => 'percent', 'value' => 10, 'label' => 'Giảm 10% tổng đơn'],
        'GIAM50K' => ['type' => 'amount',  'value' => 50000, 'label' => 'Giảm 50.000₫'],
        'FREESHIP' => ['type' => 'freeship', 'value' => 0, 'label' => 'Miễn phí vận chuyển'],
    ];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function freeshipThreshold(): int
    {
        return $this->freeshipThreshold;
    }

    public function standardShippingFee(): int
    {
        return $this->standardShippingFee;
    }

    public function expressShippingFee(): int
    {
        return $this->expressShippingFee;
    }

    public function findVoucher(?string $code, ?int $subtotal = null): ?array
    {
        if ($code === null) {
            return null;
        }

        $code = strtoupper(trim($code));

        try {
            $voucher = Voucher::findValid($code, $subtotal);
            if ($voucher) {
                return [
                    'type' => $voucher->type === 'fixed' ? 'amount' : $voucher->type,
                    'value' => $voucher->value,
                    'max_discount' => $voucher->max_discount,
                    'label' => $voucher->type_label,
                    'model' => $voucher,
                ];
            }
        } catch (\Exception) {

        }

        return $this->vouchers[$code] ?? null;
    }

    public function vouchers(): array
    {
        return $this->vouchers;
    }
}
