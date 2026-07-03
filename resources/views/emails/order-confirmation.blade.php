<x-mail::message>
# Cảm ơn bạn đã đặt hàng! 🎉

Xin chào **{{ $order->customer->name ?? 'Quý khách' }}**,

Đơn hàng **#{{ $order->id }}** của bạn đã được tiếp nhận và đang chờ xác nhận.

---

## Chi tiết đơn hàng

<x-mail::table>
| Sản phẩm | SL | Đơn giá | Thành tiền |
|:---------|:--:|--------:|-----------:|
@foreach($order->details as $detail)
| {{ $detail->product->name ?? 'SP đã xoá' }} | {{ $detail->number }} | {{ number_format($detail->price) }}₫ | {{ number_format($detail->price * $detail->number) }}₫ |
@endforeach
</x-mail::table>

**Tạm tính:** {{ number_format($order->subtotal) }}₫
**Phí ship:** {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee).'₫' : 'Miễn phí' }}
@if($order->discount_amount > 0)
**Giảm giá:** -{{ number_format($order->discount_amount) }}₫
@endif
**Tổng cộng: {{ number_format($order->total) }}₫**

---

**Thanh toán:** {{ $order->payment_method_label }}
**Vận chuyển:** {{ $order->shipping_method_label }}

<x-mail::button :url="route('orders.detail', $order->id)">
Xem chi tiết đơn hàng
</x-mail::button>

Trân trọng,
**{{ config('app.name') }}**
</x-mail::message>
