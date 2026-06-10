@extends('frontend.layouts.app')
@section('title', 'Thanh toán - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1><i class="fa fa-qrcode" style="color:var(--red);margin-right:8px;"></i>Thanh toán đơn hàng #{{ $order->id }}</h1>
</div>

<div class="payment-wrap">
    <div class="payment-qr-card">
        <div class="payment-method-badge payment-method-badge-{{ $order->payment_method }}">
            {{ $order->payment_method_label }}
        </div>

        <img src="{{ $qrUrl }}" alt="QR thanh toán" class="payment-qr-img">

        <p class="payment-amount">{{ number_format($order->total) }}₫</p>
        <p class="payment-note">
            Quét mã QR bằng ứng dụng {{ $order->payment_method_label }} để thanh toán.<br>
            <strong>Đây là mã QR demo</strong>, không kết nối cổng thanh toán thật.
        </p>

        <form action="{{ route('payment.confirm', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-checkout">
                <i class="fa fa-check-circle me-1"></i> Tôi đã thanh toán
            </button>
        </form>

        <a href="{{ route('home') }}" class="btn-continue mt-2">Quay lại trang chủ</a>
    </div>

    <div class="payment-order-summary">
        <h5 style="font-weight:700;margin-bottom:14px;">Chi tiết đơn hàng</h5>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->details as $detail)
                <tr>
                    <td>{{ $detail->product->name ?? 'Sản phẩm #' . $detail->product_id }}</td>
                    <td>{{ $detail->number }}</td>
                    <td>{{ number_format($detail->subtotal) }}₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right;padding-right:20px;">Tạm tính:</td>
                    <td>{{ number_format($order->subtotal) }}₫</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right;padding-right:20px;">Phí vận chuyển ({{ $order->shipping_method_label }}):</td>
                    <td>{{ number_format($order->shipping_fee) }}₫</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td colspan="2" style="text-align:right;padding-right:20px;">Giảm giá @if($order->voucher_code)({{ $order->voucher_code }})@endif:</td>
                    <td>-{{ number_format($order->discount_amount) }}₫</td>
                </tr>
                @endif
                <tr class="cart-total-row">
                    <td colspan="2" style="text-align:right;padding-right:20px;">Tổng cộng:</td>
                    <td><span class="cart-total-price">{{ number_format($order->total) }}₫</span></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
