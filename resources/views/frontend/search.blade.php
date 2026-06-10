@extends('frontend.layouts.app')
@section('title', 'Tìm kiếm - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1><i class="fa fa-search" style="color:var(--red);margin-right:8px;"></i>Kết quả tìm kiếm</h1>
</div>

<div class="search-filters">
    <form action="{{ route('search') }}" method="GET" class="d-flex gap-3 flex-wrap w-100 align-items-end">
        <div class="form-group" style="flex:2;min-width:180px;">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="keyword" class="form-input" placeholder="Nhập tên sản phẩm..." value="{{ $keyword }}">
        </div>
        <div class="form-group" style="flex:1;min-width:130px;">
            <label class="form-label">Giá từ (₫)</label>
            <input type="number" name="fromPrice" class="form-input" placeholder="0" value="{{ $fromPrice }}">
        </div>
        <div class="form-group" style="flex:1;min-width:130px;">
            <label class="form-label">Đến (₫)</label>
            <input type="number" name="toPrice" class="form-input" placeholder="999.000.000" value="{{ $toPrice }}">
        </div>
        <div>
            <button type="submit" class="btn-search-submit">
                <i class="fa fa-search me-1"></i> Tìm kiếm
            </button>
        </div>
    </form>
</div>

<p class="search-result-count">Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm.</p>

@forelse($products as $product)
    @if($loop->first)<div class="products-grid products-grid-4">@endif
    @include('frontend.partials.product-card')
    @if($loop->last)</div>@endif
@empty
    <div class="empty-state">
        <div class="empty-icon">🔍</div>
        <p>Không tìm thấy sản phẩm phù hợp.</p>
        <a href="{{ route('home') }}" class="btn-continue">Về trang chủ</a>
    </div>
@endforelse

<div>{{ $products->links() }}</div>
@endsection
