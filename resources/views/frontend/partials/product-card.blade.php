<div class="product-card">
    {{-- Image --}}
    <div class="card-img-wrap">
        @if($product->discount > 0)
            <span class="badge-discount">-{{ $product->discount }}%</span>
        @endif
        @if($product->hot)
            <span class="badge-hot">HOT</span>
        @endif
        <a href="{{ route('products.detail', $product->id) }}">
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" loading="lazy"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'200\'%3E%3Crect width=\'200\' height=\'200\' fill=\'%23f5f5f5\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23ccc\' font-size=\'40\'%3E📦%3C/text%3E%3C/svg%3E'">
        </a>
        <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="wishlist-btn" title="Yêu thích">♥</button>
        </form>
    </div>

    {{-- Body --}}
    <div class="card-body">
        <a href="{{ route('products.detail', $product->id) }}" class="product-name">{{ $product->name }}</a>

        @if($product->discount > 0)
            <div class="price-original">{{ number_format($product->price) }}₫</div>
        @endif
        <div class="price-final">{{ number_format($product->final_price) }}₫</div>

        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-add-cart" data-ajax-cart>
                <i class="fa fa-cart-plus"></i> Thêm vào giỏ
            </button>
        </form>
    </div>
</div>
