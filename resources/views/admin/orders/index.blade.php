@extends('admin.layouts.app')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="card">
    <div class="card-header">Danh sách đơn hàng</div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Khách hàng</th><th>Email</th><th>Ngày đặt</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->customer->email ?? '' }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="label label-{{ $order->status ? 'success' : 'warning' }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-xs btn-info">Chi tiết</a>
                        @if(!$order->status)
                            <form action="{{ route('admin.orders.deliver', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-xs btn-success" onclick="return confirm('Xác nhận đã giao hàng?')">Xác nhận giao</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Chưa có đơn hàng nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $orders->links() }}</div>
</div>
@endsection
