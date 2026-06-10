@extends('admin.layouts.app')
@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Thông tin đơn hàng</div>
            <div class="card-body">
                <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong>
                    <span class="label label-{{ $order->status ? 'success' : 'warning' }}">{{ $order->status_label }}</span>
                </p>
                <hr>
                <p><strong>Khách hàng:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email ?? '' }}</p>
                <p><strong>Phone:</strong> {{ $order->customer->phone ?? '' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->customer->address ?? '' }}</p>
                <hr>
                <p><strong>Thanh toán:</strong> {{ $order->payment_method_label }} ({{ $order->payment_status_label }})</p>
                <p><strong>Vận chuyển:</strong> {{ $order->shipping_method_label }}</p>
                @if($order->voucher_code)
                    <p><strong>Voucher:</strong> {{ $order->voucher_code }} (-{{ number_format($order->discount_amount) }}₫)</p>
                @endif
                @if(!$order->status)
                    <form action="{{ route('admin.orders.deliver', $order->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-block" onclick="return confirm('Xác nhận giao hàng?')">
                            <i class="fa fa-check"></i> Xác nhận đã giao hàng
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Sản phẩm trong đơn</div>
            <div class="card-body" style="padding:0;">
                <table class="table" style="margin:0;">
                    <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th></tr></thead>
                    <tbody>
                        @foreach($order->details as $detail)
                        <tr>
                            <td>
                                @if($detail->product)
                                    <img src="{{ $detail->product->photo_url }}" style="height:40px;margin-right:8px;">
                                    {{ $detail->product->name }}
                                @else
                                    <em>Sản phẩm đã xóa</em>
                                @endif
                            </td>
                            <td>{{ number_format($detail->price) }}₫</td>
                            <td>{{ $detail->number }}</td>
                            <td>{{ number_format($detail->subtotal) }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right">Tạm tính:</td>
                            <td>{{ number_format($order->subtotal) }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">Phí vận chuyển:</td>
                            <td>{{ number_format($order->shipping_fee) }}₫</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td colspan="3" class="text-right">Giảm giá:</td>
                            <td>-{{ number_format($order->discount_amount) }}₫</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td><strong style="color:#d32f2f;font-size:16px;">{{ number_format($order->total) }}₫</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-default" style="margin-top:10px;">← Quay lại</a>
    </div>
</div>
@endsection
