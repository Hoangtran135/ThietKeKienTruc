@extends('frontend.layouts.app')
@section('title', ($category ? $category->name : 'Sản phẩm') . ' - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1>{{ $category ? $category->name : 'Tất cả sản phẩm' }}</h1>
    <span class="bc ms-auto">
        <a href="{{ route('home') }}">Trang chủ</a>
        <i class="fa fa-chevron-right mx-1" style="font-size:10px;"></i>
        {{ $category ? $category->name : 'Sản phẩm' }}
    </span>
</div>

{{-- Sort bar --}}
<div class="sort-bar">
    <span class="sort-label">Sắp xếp:</span>
    <a href="{{ request()->fullUrlWithQuery(['order' => 'newest']) }}"
       class="btn-sort {{ $order === 'newest' ? 'active' : '' }}">Mới nhất</a>
    <a href="{{ request()->fullUrlWithQuery(['order' => 'priceAsc']) }}"
       class="btn-sort {{ $order === 'priceAsc' ? 'active' : '' }}">Giá tăng dần</a>
    <a href="{{ request()->fullUrlWithQuery(['order' => 'priceDesc']) }}"
       class="btn-sort {{ $order === 'priceDesc' ? 'active' : '' }}">Giá giảm dần</a>
    <a href="{{ request()->fullUrlWithQuery(['order' => 'nameAsc']) }}"
       class="btn-sort {{ $order === 'nameAsc' ? 'active' : '' }}">A → Z</a>
</div>

@forelse($products as $product)
    @if($loop->first)
        <div class="products-grid products-grid-4">
    @endif
    @include('frontend.partials.product-card')
    @if($loop->last)
        </div>
    @endif
@empty
    <div class="empty-state">
        <div class="empty-icon">📦</div>
        <p>Không có sản phẩm nào trong danh mục này.</p>
        <a href="{{ route('home') }}" class="btn-continue">Về trang chủ</a>
    </div>
@endforelse

<div>{{ $products->links() }}</div>
@endsection
