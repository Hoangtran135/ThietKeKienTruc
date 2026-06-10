@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" style="background:#3498db;">
            <h2>{{ $totalProducts }}</h2>
            <p><i class="fa fa-box"></i> Sản phẩm</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#2ecc71;">
            <h2>{{ $totalOrders }}</h2>
            <p><i class="fa fa-shopping-cart"></i> Đơn hàng</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#e74c3c;">
            <h2>{{ $pendingOrders }}</h2>
            <p><i class="fa fa-clock-o"></i> Chờ xử lý</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:#9b59b6;">
            <h2>{{ $totalCustomers }}</h2>
            <p><i class="fa fa-users"></i> Khách hàng</p>
        </div>
    </div>
</div>

<div class="card" style="margin-top:20px;">
    <div class="card-header">Đơn hàng gần đây</div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Khách hàng</th><th>Ngày đặt</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="label label-{{ $order->status ? 'success' : 'warning' }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td><a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-xs btn-info">Xem</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
