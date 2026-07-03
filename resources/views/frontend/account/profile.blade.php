@extends('frontend.layouts.app')
@section('title', 'Tài khoản của tôi - MediaMart')

@section('content')
<div class="page-title-bar">
    <h1><i class="fa fa-user" style="color:var(--red);margin-right:8px;"></i>Tài khoản của tôi</h1>
    <span class="bc ms-auto">
        <a href="{{ route('home') }}">Trang chủ</a>
        <i class="fa fa-chevron-right mx-1" style="font-size:10px;"></i>
        Tài khoản
    </span>
</div>

@if(session('success'))
<div class="alert alert-success mb-3" style="border-radius:8px;">
    <i class="fa fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="row g-4">

    {{-- Sidebar --}}
    <div class="col-md-3">
        <div class="myorder-info-card text-center" style="padding:20px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--primary);color:#fff;font-size:28px;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div style="font-weight:600;font-size:15px;">{{ $customer->name }}</div>
            <div style="font-size:12px;color:var(--gray-500);margin-bottom:14px;">{{ $customer->email }}</div>
            <a href="{{ route('orders.index') }}" class="btn-wishlist-lg" style="display:block;text-align:center;margin-bottom:8px;">
                <i class="fa fa-box"></i> Đơn hàng của tôi
            </a>
            <a href="{{ route('wishlist.index') }}" class="btn-wishlist-lg" style="display:block;text-align:center;">
                <i class="fa fa-heart"></i> Danh sách yêu thích
            </a>
        </div>
    </div>

    {{-- Main --}}
    <div class="col-md-9">

        {{-- Thông tin cá nhân --}}
        <div class="myorder-info-card mb-4">
            <h6 class="myorder-info-title"><i class="fa fa-edit me-2"></i>Thông tin cá nhân</h6>
            <form action="{{ route('account.profile.update') }}" method="POST" style="margin-top:12px;">
                @csrf
                @if($errors->has('name') || $errors->has('phone') || $errors->has('address'))
                <div class="alert alert-danger" style="font-size:13px;padding:10px 14px;border-radius:6px;margin-bottom:12px;">
                    {{ $errors->first() }}
                </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Họ và tên <span style="color:var(--red);">*</span></label>
                        <input type="text" name="name" class="form-input" value="{{ old('name', $customer->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Email</label>
                        <input type="email" class="form-input" value="{{ $customer->email }}" disabled style="opacity:.6;cursor:not-allowed;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Số điện thoại</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $customer->phone) }}" placeholder="0xxxxxxxxx">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Địa chỉ giao hàng</label>
                        <input type="text" name="address" class="form-input" value="{{ old('address', $customer->address) }}" placeholder="Số nhà, đường, phường, quận...">
                    </div>
                </div>
                <button type="submit" class="btn-buy mt-3" style="padding:10px 28px;">
                    <i class="fa fa-save"></i> Lưu thay đổi
                </button>
            </form>
        </div>

        {{-- Đổi mật khẩu --}}
        <div class="myorder-info-card">
            <h6 class="myorder-info-title"><i class="fa fa-lock me-2"></i>Đổi mật khẩu</h6>
            <form action="{{ route('account.password.change') }}" method="POST" style="margin-top:12px;">
                @csrf
                @if($errors->has('current_password') || $errors->has('password'))
                <div class="alert alert-danger" style="font-size:13px;padding:10px 14px;border-radius:6px;margin-bottom:12px;">
                    {{ $errors->first() }}
                </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-input" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" style="font-size:13px;font-weight:500;">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn-buy mt-3" style="padding:10px 28px;background:#1a73e8;">
                    <i class="fa fa-key"></i> Đổi mật khẩu
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
