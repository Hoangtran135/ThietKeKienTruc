<?php

namespace App\Services\Shipping;

/**
 * Adapter Pattern: GHN và GHTK là 2 "SDK bên thứ ba" giả lập, mỗi SDK có
 * phương thức/tham số trả về khác nhau. Các Adapter bên dưới chuyển đổi
 * (adapt) chúng về cùng một interface ShippingProvider để hệ thống dùng
 * thống nhất, không phụ thuộc vào chi tiết của từng SDK.
 */
interface ShippingProvider
{
    public function getName(): string;

    public function getFee(int $orderTotal): int;
}

/**
 * SDK giả lập của GHN - trả về mảng kết quả với cấu trúc riêng.
 */
class GhnApiClient
{
    public function calculateFee(array $payload): array
    {
        $orderTotal = $payload['order_value'] ?? 0;

        return [
            'code' => 200,
            'data' => [
                'shipping_fee' => $orderTotal >= 500000 ? 0 : 35000,
            ],
        ];
    }
}

/**
 * SDK giả lập của GHTK - trả về số tiền dạng float trực tiếp.
 */
class GhtkApiSdk
{
    public function estimateShippingCost(float $orderAmount): float
    {
        return $orderAmount >= 500000 ? 0.0 : 25000.0;
    }
}

/**
 * Adapter cho GHN: chuyển đổi calculateFee() -> getFee().
 */
class GhnShippingAdapter implements ShippingProvider
{
    public function __construct(private GhnApiClient $client = new GhnApiClient()) {}

    public function getName(): string
    {
        return 'GHN (Giao hàng nhanh)';
    }

    public function getFee(int $orderTotal): int
    {
        $result = $this->client->calculateFee(['order_value' => $orderTotal]);

        return (int) $result['data']['shipping_fee'];
    }
}

/**
 * Adapter cho GHTK: chuyển đổi estimateShippingCost() -> getFee().
 */
class GhtkShippingAdapter implements ShippingProvider
{
    public function __construct(private GhtkApiSdk $client = new GhtkApiSdk()) {}

    public function getName(): string
    {
        return 'GHTK (Giao hàng tiết kiệm)';
    }

    public function getFee(int $orderTotal): int
    {
        return (int) $this->client->estimateShippingCost((float) $orderTotal);
    }
}
