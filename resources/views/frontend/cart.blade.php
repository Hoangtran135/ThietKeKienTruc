@extends('frontend.layouts.app')
@section('title', 'Giỏ hàng - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1><i class="fa fa-shopping-cart" style="color:var(--red);margin-right:8px;"></i>Giỏ hàng của bạn</h1>
    @if(!empty($cart))
        <span class="bc ms-auto">{{ count($cart) }} sản phẩm</span>
    @endif
</div>

@if(empty($cart))
    <div class="cart-wrap empty-state">
        <div class="empty-icon">🛒</div>
        <p>Giỏ hàng của bạn đang trống.</p>
        <a href="{{ route('home') }}" class="btn-continue">Tiếp tục mua sắm</a>
    </div>
@else
<div class="cart-wrap">
    <form action="{{ route('cart.update') }}" method="POST">
        @csrf
        <div style="overflow-x:auto;">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('uploads/products/' . $item['photo']) }}" class="cart-thumb" onerror="this.src='https://via.placeholder.com/56'">
                                <a href="{{ route('products.detail', $id) }}" style="font-weight:500;font-size:14px;">{{ $item['name'] }}</a>
                            </div>
                        </td>
                        <td style="color:var(--red);font-weight:600;">{{ number_format($item['price']) }}₫</td>
                        <td>
                            <input type="number" name="product_{{ $id }}" value="{{ $item['number'] }}"
                                   min="0" class="cart-qty">
                        </td>
                        <td style="font-weight:700;">{{ number_format($item['price'] * $item['number']) }}₫</td>
                        <td>

                            <button type="submit" form="remove-form-{{ $id }}" class="btn-remove" title="Xóa">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="cart-total-row">
                        <td colspan="3" style="text-align:right;padding-right:20px;">Tổng cộng:</td>
                        <td colspan="2"><span class="cart-total-price">{{ number_format($total) }}₫</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-cart-update">
                    <i class="fa fa-sync-alt me-1"></i> Cập nhật giỏ
                </button>
                <a href="{{ route('home') }}" class="btn-cart-update" style="text-decoration:none;">
                    <i class="fa fa-arrow-left me-1"></i> Tiếp tục mua
                </a>
            </div>
        </div>
    </form>

    @foreach($cart as $id => $item)
        <form id="remove-form-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="d-none">
            @csrf @method('DELETE')
        </form>
    @endforeach

    <div class="payment-method-box mt-4">
        <form id="checkout-form" action="{{ route('cart.checkout') }}" method="POST">
            @csrf

            <h5 style="font-weight:700;margin-bottom:14px;">Phương thức vận chuyển</h5>
            <div class="payment-method-list mb-3">
                @foreach($shippingOptions as $i => $option)
                <label class="payment-method-item">
                    <input type="radio" name="shipping_method" class="shipping-method-radio" value="{{ $option['code'] }}" {{ $i === 0 ? 'checked' : '' }}>
                    <span class="payment-method-icon"><i class="fa fa-truck"></i></span>
                    <span>
                        <strong>{{ $option['label'] }}</strong>
                        <small>Phí: {{ number_format($option['fee']) }}₫ (miễn phí nếu đơn từ 500.000₫)</small>
                    </span>
                </label>
                @endforeach
            </div>

            <h5 style="font-weight:700;margin-bottom:14px;">Mã giảm giá</h5>
            <div class="mb-3 d-flex gap-2">
                <input type="text" id="voucher-code-input" name="voucher_code" class="form-control"
                       placeholder="Nhập mã voucher (VD: GIAM10, GIAM50K, FREESHIP)">
                <button type="button" id="voucher-apply-btn" class="btn-cart-update" style="white-space:nowrap;">
                    Áp dụng
                </button>
            </div>
            <div id="voucher-message" class="mb-3" style="font-size:13px;"></div>

            <div id="order-summary-box" style="background:var(--gray-100, #f7f7f7);border-radius:8px;padding:14px 16px;margin-bottom:16px;">
                <div class="d-flex justify-content-between mb-1">
                    <span>Tạm tính:</span>
                    <span id="summary-subtotal">{{ number_format($total) }}₫</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>Phí vận chuyển:</span>
                    <span id="summary-shipping">{{ number_format($shippingOptions[0]['fee'] ?? 0) }}₫</span>
                </div>
                <div class="d-flex justify-content-between mb-1" id="summary-discount-row" style="display:none !important;">
                    <span>Giảm giá:</span>
                    <span id="summary-discount" style="color:var(--red);">-0₫</span>
                </div>
                <hr style="margin:8px 0;">
                <div class="d-flex justify-content-between" style="font-weight:700;font-size:16px;">
                    <span>Tổng cộng:</span>
                    <span id="summary-total">{{ number_format($total + ($shippingOptions[0]['fee'] ?? 0)) }}₫</span>
                </div>
            </div>

            <h5 style="font-weight:700;margin-bottom:14px;">Phương thức thanh toán</h5>
            <div class="payment-method-list">
                <label class="payment-method-item">
                    <input type="radio" name="payment_method" value="cod" checked>
                    <span class="payment-method-icon"><i class="fa fa-money-bill-wave"></i></span>
                    <span>
                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                        <small>Thanh toán bằng tiền mặt khi nhận sản phẩm</small>
                    </span>
                </label>
                <label class="payment-method-item">
                    <input type="radio" name="payment_method" value="vnpay">
                    <span class="payment-method-icon" style="color:#0066b3;"><i class="fa fa-qrcode"></i></span>
                    <span>
                        <strong>VNPay</strong>
                        <small>Quét mã QR để thanh toán qua VNPay (demo)</small>
                    </span>
                </label>
                <label class="payment-method-item">
                    <input type="radio" name="payment_method" value="momo">
                    <span class="payment-method-icon" style="color:#a50064;"><i class="fa fa-qrcode"></i></span>
                    <span>
                        <strong>Ví Momo</strong>
                        <small>Quét mã QR để thanh toán qua Momo (demo)</small>
                    </span>
                </label>
            </div>

            <button type="submit" class="btn-checkout mt-3">
                <i class="fa fa-credit-card me-1"></i> Đặt hàng ngay
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const applyBtn      = document.getElementById('voucher-apply-btn');
    const voucherInput  = document.getElementById('voucher-code-input');
    const messageBox    = document.getElementById('voucher-message');
    const checkoutForm  = document.getElementById('checkout-form');
    if (!applyBtn || !checkoutForm) return;

    const csrfToken = checkoutForm.querySelector('input[name="_token"]').value;

    function currentShippingMethod() {
        const checked = checkoutForm.querySelector('.shipping-method-radio:checked');
        return checked ? checked.value : 'standard';
    }

    function formatVnd(n) {
        return new Intl.NumberFormat('vi-VN').format(n) + '₫';
    }

    function updateSummary(voucherCode) {
        messageBox.textContent = '';
        messageBox.style.color = '';

        fetch(`{{ route('cart.preview') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                shipping_method: currentShippingMethod(),
                voucher_code: voucherCode,
            }),
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    messageBox.textContent = data.message || 'Có lỗi xảy ra.';
                    messageBox.style.color = '#e74c3c';
                    return;
                }

                document.getElementById('summary-subtotal').textContent = formatVnd(data.subtotal);
                document.getElementById('summary-shipping').textContent = data.shippingFee > 0 ? formatVnd(data.shippingFee) : 'Miễn phí';
                document.getElementById('summary-total').textContent = formatVnd(data.total);

                const discountRow = document.getElementById('summary-discount-row');
                if (data.discount > 0) {
                    document.getElementById('summary-discount').textContent = '-' + formatVnd(data.discount);
                    discountRow.style.setProperty('display', 'flex');
                } else {
                    discountRow.style.setProperty('display', 'none', 'important');
                }

                if (voucherCode) {
                    if (data.voucherValid) {
                        messageBox.textContent = 'Áp dụng mã giảm giá thành công!';
                        messageBox.style.color = '#27ae60';
                    } else {
                        messageBox.textContent = data.voucherMessage || 'Mã voucher không hợp lệ.';
                        messageBox.style.color = '#e74c3c';
                    }
                }
            })
            .catch(() => {
                messageBox.textContent = 'Không thể kết nối máy chủ, vui lòng thử lại.';
                messageBox.style.color = '#e74c3c';
            });
    }

    applyBtn.addEventListener('click', () => updateSummary(voucherInput.value.trim()));

    checkoutForm.querySelectorAll('.shipping-method-radio').forEach(radio => {
        radio.addEventListener('change', () => updateSummary(voucherInput.value.trim()));
    });
})();
</script>
@endpush
@endif
@endsection
