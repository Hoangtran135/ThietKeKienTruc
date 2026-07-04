<?php

namespace App\Services\Shipping;

use App\Support\SiteSettings;
use InvalidArgumentException;

interface ShippingFeeStrategy
{
    public function code(): string;

    public function label(): string;

    public function calculate(int $subtotal): int;
}

class StandardShippingStrategy implements ShippingFeeStrategy
{
    public function __construct(private ShippingProvider $provider = new GhtkShippingAdapter) {}

    public function code(): string
    {
        return 'standard';
    }

    public function label(): string
    {
        return 'Giao hàng tiêu chuẩn ('.$this->provider->getName().')';
    }

    public function calculate(int $subtotal): int
    {
        return $this->provider->getFee($subtotal);
    }
}

class ExpressShippingStrategy implements ShippingFeeStrategy
{
    public function __construct(private ShippingProvider $provider = new GhnShippingAdapter) {}

    public function code(): string
    {
        return 'express';
    }

    public function label(): string
    {
        return 'Giao hàng hỏa tốc ('.$this->provider->getName().')';
    }

    public function calculate(int $subtotal): int
    {
        $fee = $this->provider->getFee($subtotal);

        return $fee + 15000;
    }
}

class FreeShippingStrategy implements ShippingFeeStrategy
{
    public function code(): string
    {
        return 'free';
    }

    public function label(): string
    {
        return 'Miễn phí vận chuyển';
    }

    public function calculate(int $subtotal): int
    {
        return 0;
    }
}

class ShippingFeeCalculator
{
    public static function make(string $code): ShippingFeeStrategy
    {
        return match ($code) {
            'standard' => new StandardShippingStrategy,
            'express' => new ExpressShippingStrategy,
            'free' => new FreeShippingStrategy,
            default => throw new InvalidArgumentException("Phương thức vận chuyển không hợp lệ: {$code}"),
        };
    }

    public static function all(): array
    {
        return [
            new StandardShippingStrategy,
            new ExpressShippingStrategy,
        ];
    }

    public static function resolve(string $code, int $subtotal): ShippingFeeStrategy
    {
        if ($subtotal >= SiteSettings::getInstance()->freeshipThreshold()) {
            return new FreeShippingStrategy;
        }

        return self::make($code);
    }
}
