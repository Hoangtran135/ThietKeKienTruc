@extends('admin.layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
.stat-card { border-radius:8px; color:#fff; padding:20px 24px; display:flex; align-items:center; gap:16px; }
.stat-card .stat-icon { font-size:36px; opacity:.8; }
.stat-card .stat-body h2 { margin:0; font-size:28px; font-weight:700; }
.stat-card .stat-body p  { margin:4px 0 0; font-size:13px; opacity:.85; }
.stat-card.blue   { background:linear-gradient(135deg,#2980b9,#3498db); }
.stat-card.green  { background:linear-gradient(135deg,#27ae60,#2ecc71); }
.stat-card.red    { background:linear-gradient(135deg,#c0392b,#e74c3c); }
.stat-card.purple { background:linear-gradient(135deg,#8e44ad,#9b59b6); }
.stat-card.orange { background:linear-gradient(135deg,#d35400,#e67e22); }
</style>
@endpush

@section('content')

{{-- Stat Cards --}}
<div class="row" style="margin-bottom:20px;">
    <div class="col-md-2 col-sm-4">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fa fa-box"></i></div>
            <div class="stat-body"><h2>{{ $totalProducts }}</h2><p>Sản phẩm</p></div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4">
        <div class="stat-card green">
            <div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
            <div class="stat-body"><h2>{{ $totalOrders }}</h2><p>Đơn hàng</p></div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4">
        <div class="stat-card red">
            <div class="stat-icon"><i class="fa fa-clock-o"></i></div>
            <div class="stat-body"><h2>{{ $pendingOrders }}</h2><p>Chờ xử lý</p></div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="fa fa-users"></i></div>
            <div class="stat-body"><h2>{{ $totalCustomers }}</h2><p>Khách hàng</p></div>
        </div>
    </div>
    <div class="col-md-4 col-sm-8">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="fa fa-money"></i></div>
            <div class="stat-body">
                <h2 style="font-size:20px;">{{ number_format($totalRevenue) }}₫</h2>
                <p>Doanh thu (đơn đã giao)</p>
            </div>
        </div>
    </div>
</div>

{{-- Trạng thái đơn hàng --}}
<div class="row" style="margin-bottom:20px;">
    @php
    $statusMap = [0=>'Chờ xử lý',1=>'Đã xác nhận',2=>'Đang giao',3=>'Đã giao',4=>'Đã huỷ'];
    $statusColors = [0=>'warning',1=>'info',2=>'primary',3=>'success',4=>'danger'];
    @endphp
    @foreach($statusMap as $s => $lbl)
    <div class="col-md-2 col-sm-4" style="margin-bottom:10px;">
        <a href="{{ route('admin.orders.index', ['status' => $s]) }}" style="text-decoration:none;">
            <div style="background:#fff;border-radius:6px;padding:12px 16px;box-shadow:0 1px 3px rgba(0,0,0,.08);border-left:4px solid var(--bs-{{ $statusColors[$s] ?? 'secondary' }},#999);text-align:center;">
                <div style="font-size:20px;font-weight:700;">{{ $statusStats[$s] ?? 0 }}</div>
                <div style="font-size:12px;color:#666;">{{ $lbl }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="row">
    {{-- Biểu đồ doanh thu --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Doanh thu 12 tháng gần nhất</div>
            <div class="card-body">
                <canvas id="revenueChart" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- Top sản phẩm --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Top 5 sản phẩm bán chạy</div>
            <div class="card-body" style="padding:0;">
                <table class="table" style="margin:0;">
                    <thead><tr><th>#</th><th>Sản phẩm</th><th>Đã bán</th></tr></thead>
                    <tbody>
                        @forelse($topProducts as $i => $item)
                        <tr>
                            <td>
                                <span style="background:{{ ['#f39c12','#95a5a6','#cd7f32','#3498db','#2ecc71'][$i] ?? '#ddd' }};color:#fff;width:22px;height:22px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">
                                    {{ $i+1 }}
                                </span>
                            </td>
                            <td style="font-size:13px;">{{ Str::limit($item->product->name ?? '—', 30) }}</td>
                            <td><strong>{{ $item->total_sold }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Đơn hàng gần đây --}}
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        Đơn hàng gần đây
        <a href="{{ route('admin.orders.index') }}" class="btn btn-xs btn-default">Xem tất cả</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Khách hàng</th><th>Tổng tiền</th><th>Ngày đặt</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ number_format($order->total) }}₫</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="label label-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    </td>
                    <td><a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-xs btn-info">Xem</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels  = @json($revenueChartData->pluck('label'));
const amounts = @json($revenueChartData->pluck('amount'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: amounts,
            backgroundColor: 'rgba(52,152,219,0.7)',
            borderColor: '#2980b9',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => new Intl.NumberFormat('vi-VN').format(ctx.raw) + '₫'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => new Intl.NumberFormat('vi-VN', {notation:'compact'}).format(v) + '₫'
                }
            }
        }
    }
});
</script>
@endpush
@endsection
