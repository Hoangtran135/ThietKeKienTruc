@extends('frontend.layouts.app')
@section('title', $product->name . ' - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1 style="font-size:15px;font-weight:500;">{{ Str::limit($product->name, 60) }}</h1>
    <span class="bc ms-auto">
        <a href="{{ route('home') }}">Trang chủ</a>
        <i class="fa fa-chevron-right mx-1" style="font-size:10px;"></i>
        <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}">{{ $product->category->name ?? 'Sản phẩm' }}</a>
        <i class="fa fa-chevron-right mx-1" style="font-size:10px;"></i>
        {{ Str::limit($product->name, 30) }}
    </span>
</div>

<div class="detail-wrap">
    <div class="row g-4">

        <div class="col-md-5">
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" class="detail-img"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect width=\'400\' height=\'400\' fill=\'%23f5f5f5\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23ccc\' font-size=\'60\'%3E📦%3C/text%3E%3C/svg%3E'">
        </div>

        <div class="col-md-7">
            <p class="detail-category">
                Danh mục:
                <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}">
                    {{ $product->category->name ?? '' }}
                </a>
            </p>
            <h1 class="detail-name">{{ $product->name }}</h1>

            <div class="d-flex align-items-center gap-2 mb-3">
                @php $stars = round($avgRating); @endphp
                <div style="color:#ffc107;font-size:16px;">
                    @for($i=1;$i<=5;$i++)
                        <i class="fa{{ $i <= $stars ? 's' : 'r' }} fa-star"></i>
                    @endfor
                </div>
                <span style="font-size:13px;color:var(--gray-700);">{{ number_format($avgRating,1) }}/5 ({{ $product->ratings->count() }} đánh giá)</span>
            </div>

            <div class="detail-price-box">
                @if($product->discount > 0)
                    <div class="detail-price-original">{{ number_format($product->price) }}₫</div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="detail-price-final">{{ number_format($product->final_price) }}₫</span>
                        <span class="detail-discount-badge">-{{ $product->discount }}%</span>
                    </div>
                    <div style="font-size:13px;color:#c62828;margin-top:4px;">
                        Tiết kiệm: {{ number_format($product->price - $product->final_price) }}₫
                    </div>
                @else
                    <div class="detail-price-final">{{ number_format($product->final_price) }}₫</div>
                @endif
            </div>

            <p class="detail-desc">{!! $product->description !!}</p>
            <div style="margin-bottom:12px;">{!! $product->stock_label !!}</div>

            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-buy" data-ajax-cart>
                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </form>
                <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-wishlist-lg">
                        <i class="fa fa-heart"></i> Yêu thích
                    </button>
                </form>
            </div>

            <div class="rating-wrap">
                <h5><i class="fa fa-star" style="color:#ffc107;"></i> Đánh giá sản phẩm</h5>
                @if($canRate)
                    <form action="{{ route('products.rating', $product->id) }}" method="POST">
                        @csrf
                        <div class="star-btns" id="starBtns">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" onclick="selectStar({{ $i }})">
                                    {{ $i }} ★
                                </button>
                                <input type="radio" name="star" value="{{ $i }}" id="star{{ $i }}" style="display:none;">
                            @endfor
                        </div>
                        <textarea name="review" class="form-control mt-2" rows="3"
                            placeholder="Nhận xét của bạn (tuỳ chọn)..."
                            style="font-size:13px;resize:vertical;max-width:420px;"></textarea>
                        <button type="submit" class="btn-rate mt-2">Gửi đánh giá</button>
                    </form>
                @elseif($alreadyRated)
                    <div class="alert alert-success py-2 mt-2" style="font-size:13px;">
                        <i class="fa fa-check-circle"></i> Bạn đã đánh giá sản phẩm này rồi.
                    </div>
                @elseif(Auth::guard('customer')->check())
                    <div class="alert alert-warning py-2 mt-2" style="font-size:13px;">
                        <i class="fa fa-lock"></i> Bạn cần <strong>mua và nhận hàng thành công</strong> mới có thể đánh giá.
                    </div>
                @else
                    <div class="alert alert-secondary py-2 mt-2" style="font-size:13px;">
                        <i class="fa fa-user"></i> <a href="{{ route('account.login') }}">Đăng nhập</a> và mua hàng để đánh giá sản phẩm.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($product->content)
    <div class="detail-content-box">
        <h3>Mô tả chi tiết</h3>
        <div style="line-height:1.8;color:var(--gray-700);">{!! $product->content !!}</div>
    </div>
@endif

@if($product->ratings->count())
<div class="detail-content-box" style="margin-top:24px;">
    <h3>Đánh giá từ khách hàng ({{ $product->ratings->count() }})</h3>
    <div style="display:flex;flex-direction:column;gap:16px;margin-top:16px;">
        @foreach($product->ratings->sortByDesc('created_at') as $rating)
        <div style="border:1px solid var(--border);border-radius:8px;padding:14px 16px;">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:600;">
                    {{ strtoupper(substr($rating->customer->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;">{{ $rating->customer->name ?? 'Khách hàng' }}</div>
                    <div style="color:#ffc107;font-size:12px;">
                        @for($i=1;$i<=5;$i++)
                            <i class="fa{{ $i <= $rating->star ? 's' : 'r' }} fa-star"></i>
                        @endfor
                        <span style="color:var(--gray-700);font-size:11px;margin-left:4px;">{{ $rating->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @if($rating->review)
                <p style="font-size:13px;color:var(--gray-700);margin:0;margin-top:6px;">{{ $rating->review }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

@if($related->count())
    <div style="margin-top:32px;">
        <div class="mm-section-title">
            <span>Sản phẩm liên quan</span>
        </div>
        <div class="products-grid products-grid-4">
            @foreach($related as $product)
                @include('frontend.partials.product-card')
            @endforeach
        </div>
    </div>
@endif

@push('scripts')
<script>
function selectStar(n) {
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        btn.classList.toggle('active', i < n);
    });
    document.getElementById('star' + n).checked = true;
}
</script>
@endpush
@endsection
