<?php

namespace App\Support;

use App\Models\Voucher;

/**
 * Singleton Pattern: chỉ một instance cấu hình chung của shop
 * (ngưỡng freeship, phí ship mặc định, danh sách voucher...) được
 * tạo ra và dùng chung cho toàn bộ ứng dụng.
 */
class SiteSettings
{
    private static ?SiteSettings $instance = null;

    private int $freeshipThreshold = 500000;

    private int $standardShippingFee = 30000;

    private int $expressShippingFee = 60000;

    /** @var array<string, array{type: string, value: int, label: string}> */
    private array $vouchers = [
        'GIAM10'   => ['type' => 'percent', 'value' => 10, 'label' => 'Giảm 10% tổng đơn'],
        'GIAM50K'  => ['type' => 'amount',  'value' => 50000, 'label' => 'Giảm 50.000₫'],
        'FREESHIP' => ['type' => 'freeship', 'value' => 0, 'label' => 'Miễn phí vận chuyển'],
    ];

    private function __construct()
    {
        // Private constructor: không cho phép khởi tạo trực tiếp từ bên ngoài
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
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

    public function findVoucher(?string $code): ?array
    {
        if ($code === null) {
            return null;
        }

        $code = strtoupper(trim($code));

        // Ưu tiên lấy từ DB
        try {
            $voucher = Voucher::findValid($code);
            if ($voucher) {
                return [
                    'type'  => $voucher->type === 'fixed' ? 'amount' : $voucher->type,
                    'value' => $voucher->value,
                    'label' => $voucher->type_label,
                    'model' => $voucher,
                ];
            }
        } catch (\Exception) {
            // fallback nếu bảng chưa tồn tại
        }

        return $this->vouchers[$code] ?? null;
    }

    /** @return array<string, array{type: string, value: int, label: string}> */
    public function vouchers(): array
    {
        return $this->vouchers;
    }
}
