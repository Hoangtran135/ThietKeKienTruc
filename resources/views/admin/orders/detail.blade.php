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
                    <span class="label label-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </p>
                {{-- Timeline --}}
                <div style="margin:12px 0 4px;">
                    @foreach([0=>'Chờ xử lý',1=>'Xác nhận',2=>'Đang giao',3=>'Đã giao'] as $s => $lbl)
                    <div style="display:flex;align-items:center;margin-bottom:6px;">
                        <div style="width:22px;height:22px;border-radius:50%;background:{{ $order->status >= $s && $order->status != 4 ? '#27ae60' : '#ddd' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">
                            {{ $order->status >= $s && $order->status != 4 ? '✓' : ($s+1) }}
                        </div>
                        <span style="margin-left:8px;font-size:12px;color:{{ $order->status >= $s && $order->status != 4 ? '#27ae60' : '#999' }};">{{ $lbl }}</span>
                    </div>
                    @endforeach
                    @if($order->status == 4)
                    <div style="color:#e74c3c;font-size:12px;margin-top:4px;"><i class="fa fa-times-circle"></i> Đơn hàng đã bị huỷ</div>
                    @endif
                </div>
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
                @if($order->status < 4)
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="margin-top:8px;">
                    @csrf
                    @php $nextLabels = [1=>'Xác nhận đơn',2=>'Bắt đầu giao hàng',3=>'Xác nhận đã giao']; @endphp
                    @if(isset($nextLabels[$order->status + 1]))
                    <input type="hidden" name="status" value="{{ $order->status + 1 }}">
                    <button class="btn btn-success btn-block">
                        <i class="fa fa-arrow-right"></i> {{ $nextLabels[$order->status + 1] }}
                    </button>
                    @endif
                </form>
                @if($order->isCancellable())
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="margin-top:6px;"
                      onsubmit="return confirm('Huỷ đơn hàng này?')">
                    @csrf
                    <input type="hidden" name="status" value="4">
                    <button class="btn btn-danger btn-block">
                        <i class="fa fa-times"></i> Huỷ đơn hàng
                    </button>
                </form>
                @endif
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
