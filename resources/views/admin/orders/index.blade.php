@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="card">
    <div class="card-header">
        Danh sách đơn hàng
        <a href="{{ route('admin.orders.export') }}" class="btn btn-sm btn-default">
            <i class="fa fa-download"></i> Xuất CSV
        </a>
    </div>
    <div class="card-body" style="padding:12px 20px;border-bottom:1px solid #eee;">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:10px;">{{ session('success') }}</div>
        @endif
        {{-- Lọc theo trạng thái --}}
        <div class="btn-group">
            @foreach(['' => 'Tất cả', '0' => 'Chờ xử lý', '1' => 'Đã xác nhận', '2' => 'Đang giao', '3' => 'Đã giao', '4' => 'Đã huỷ'] as $val => $label)
                <a href="{{ route('admin.orders.index', ['status' => $val]) }}"
                   class="btn btn-sm {{ (string)$status === (string)$val ? 'btn-primary' : 'btn-default' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td style="font-size:12px;">{{ $order->customer->email ?? '' }}</td>
                    <td>{{ number_format($order->total) }}₫</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="label label-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-xs btn-info">Chi tiết</a>
                        @if($order->status < 4)
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @php
                                $nextStatus = $order->status + 1;
                                $nextLabels = [1=>'Xác nhận',2=>'Giao hàng',3=>'Đã giao',4=>'Hoàn tất'];
                            @endphp
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <button class="btn btn-xs btn-success">→ {{ $nextLabels[$nextStatus] ?? '' }}</button>
                        </form>
                        @endif
                        @if($order->isCancellable())
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Huỷ đơn hàng #{{ $order->id }}?')">
                            @csrf
                            <input type="hidden" name="status" value="4">
                            <button class="btn btn-xs btn-danger">Huỷ</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $orders->links() }}</div>
</div>
@endsection
