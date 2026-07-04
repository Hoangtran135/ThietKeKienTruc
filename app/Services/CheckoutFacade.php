<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Services\Cart\AmountDiscountDecorator;
use App\Services\Cart\BaseCartPrice;
use App\Services\Cart\CartPriceComponent;
use App\Services\Cart\FreeshipDecorator;
use App\Services\Cart\PercentDiscountDecorator;
use App\Services\Order\OrderBuilder;
use App\Services\Payment\PaymentMethodFactory;
use App\Services\Shipping\ShippingFeeCalculator;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Event;

class CheckoutFacade
{
    public static function placeOrder(
        array $cart,
        ?int $customerId,
        string $paymentMethodCode,
        string $shippingMethodCode,
        ?string $voucherCode,
    ): array {
        $subtotal = (int) array_sum(array_map(fn ($i) => $i['price'] * $i['number'], $cart));

        $shippingStrategy = ShippingFeeCalculator::resolve($shippingMethodCode, $subtotal);
        $shippingFee = $shippingStrategy->calculate($subtotal);

        $priceBreakdown = self::applyVoucher(new BaseCartPrice($subtotal, $shippingFee), $voucherCode);

        $paymentMethod = PaymentMethodFactory::make($paymentMethodCode);

        $order = OrderBuilder::new()
            ->forCustomer($customerId)
            ->withPaymentMethod($paymentMethod->code())
            ->withShipping($shippingStrategy->code(), $priceBreakdown->getShippingFee())
            ->withVoucher($voucherCode, $priceBreakdown->getDiscount())
            ->addItemsFromCart($cart)
            ->build();

        Event::dispatch(new OrderPlaced($order));

        if ($voucherCode) {
            $voucherData = SiteSettings::getInstance()->findVoucher($voucherCode);
            if (isset($voucherData['model'])) {
                $voucherData['model']->increment('used_count');
            }
        }

        return [
            'order' => $order,
            'paymentMethod' => $paymentMethod,
            'priceBreakdown' => $priceBreakdown,
        ];
    }

    public static function preview(array $cart, string $shippingMethodCode, ?string $voucherCode): array
    {
        $subtotal = (int) array_sum(array_map(fn ($i) => $i['price'] * $i['number'], $cart));

        $shippingStrategy = ShippingFeeCalculator::resolve($shippingMethodCode, $subtotal);
        $shippingFee = $shippingStrategy->calculate($subtotal);

        $base = new BaseCartPrice($subtotal, $shippingFee);

        $voucherCode = $voucherCode ? trim($voucherCode) : null;
        $voucherValid = true;
        $voucherMessage = null;

        if ($voucherCode !== null && $voucherCode !== '') {
            $voucherData = SiteSettings::getInstance()->findVoucher($voucherCode, $subtotal);
            $voucherValid = $voucherData !== null;
            $voucherMessage = $voucherValid
                ? null
                : 'Mã voucher không hợp lệ, đã hết hạn, hoặc đơn hàng chưa đạt giá trị tối thiểu.';
        }

        $priceBreakdown = $voucherValid ? self::applyVoucher($base, $voucherCode) : $base;

        return [
            'subtotal' => $priceBreakdown->getSubtotal(),
            'shippingFee' => $priceBreakdown->getShippingFee(),
            'discount' => $priceBreakdown->getDiscount(),
            'total' => $priceBreakdown->getTotal(),
            'voucherValid' => $voucherValid,
            'voucherMessage' => $voucherMessage,
        ];
    }

    private static function applyVoucher(CartPriceComponent $price, ?string $voucherCode): CartPriceComponent
    {
        $voucher = SiteSettings::getInstance()->findVoucher($voucherCode, $price->getSubtotal());

        if ($voucher === null || $voucherCode === null) {
            return $price;
        }

        $code = strtoupper(trim($voucherCode));

        return match ($voucher['type']) {
            'percent' => new PercentDiscountDecorator($price, $voucher['value'], $code, $voucher['max_discount'] ?? null),
            'amount' => new AmountDiscountDecorator($price, $voucher['value'], $code),
            'freeship' => new FreeshipDecorator($price, $code),
            default => $price,
        };
    }
}
