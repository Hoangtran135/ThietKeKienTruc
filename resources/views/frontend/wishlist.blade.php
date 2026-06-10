@extends('frontend.layouts.app')
@section('title', 'Sản phẩm yêu thích - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1><i class="fa fa-heart" style="color:var(--red);margin-right:8px;"></i>Sản phẩm yêu thích</h1>
    @if(!empty($wishlist))
        <span class="bc ms-auto">{{ count($wishlist) }} sản phẩm</span>
    @endif
</div>

@if(empty($wishlist))
    <div class="cart-wrap empty-state">
        <div class="empty-icon">💝</div>
        <p>Bạn chưa có sản phẩm yêu thích nào.</p>
        <a href="{{ route('home') }}" class="btn-continue">Khám phá ngay</a>
    </div>
@else
<div class="wishlist-grid">
    @foreach($wishlist as $id => $item)
    <div class="product-card">
        <div class="card-img-wrap">
            <a href="{{ route('products.detail', $id) }}">
                <img src="{{ asset('uploads/products/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                     onerror="this.src='https://via.placeholder.com/200'">
            </a>
        </div>
        <div class="card-body">
            <a href="{{ route('products.detail', $id) }}" class="product-name">{{ $item['name'] }}</a>
            <div class="price-final">{{ number_format($item['price']) }}₫</div>
            <div class="d-flex gap-2">
                <form action="{{ route('cart.add', $id) }}" method="POST" style="flex:1;">
                    @csrf
                    <button type="submit" class="btn-add-cart"><i class="fa fa-cart-plus"></i> Thêm giỏ</button>
                </form>
                <form action="{{ route('wishlist.remove', $id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remove" title="Xóa khỏi yêu thích" style="border:1.5px solid var(--gray-200);border-radius:7px;width:36px;height:36px;">
                        <i class="fa fa-times"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
