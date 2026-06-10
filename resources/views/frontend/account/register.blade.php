@extends('frontend.layouts.app')
@section('title', 'Đăng ký - MediaMart')

@section('content')
<div class="auth-card" style="max-width:520px;">
    <div class="auth-card-header">
        <h3><i class="fa fa-user-plus me-2"></i>Đăng ký tài khoản</h3>
        <p>Tạo tài khoản để mua sắm dễ dàng hơn!</p>
    </div>
    <div class="auth-card-body">
        @if($errors->any())
            <div class="auth-errors">
                <i class="fa fa-exclamation-circle me-1"></i>
                <ul style="margin:6px 0 0;padding-left:18px;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('account.register.post') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12 form-group">
                    <label class="form-label">Họ tên <span style="color:var(--red)">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="col-12 form-group">
                    <label class="form-label">Email <span style="color:var(--red)">*</span></label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="example@gmail.com" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" placeholder="0901234567">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-input" value="{{ old('address') }}" placeholder="Địa chỉ của bạn">
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">Mật khẩu <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password" class="form-input" placeholder="Ít nhất 8 ký tự" required>
                </div>
                <div class="col-md-6 form-group">
                    <label class="form-label">Xác nhận mật khẩu <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>
            <button type="submit" class="btn-auth mt-3">Đăng ký ngay</button>
        </form>
        <div class="auth-divider">Đã có tài khoản?</div>
        <a href="{{ route('account.login') }}" class="btn-auth" style="display:block;text-align:center;background:transparent;border:2px solid var(--red);color:var(--red);margin-top:0;">
            Đăng nhập
        </a>
    </div>
</div>
@endsection
