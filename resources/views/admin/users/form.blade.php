@extends('admin.layouts.app')
@section('title', isset($user) ? 'Sửa quản trị viên' : 'Thêm quản trị viên')

@section('content')
<div class="card" style="max-width:550px;">
    <div class="card-header">
        {{ isset($user) ? 'Sửa tài khoản' : 'Thêm tài khoản mới' }}
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-default">← Quay lại</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul style="margin:0;padding-left:15px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul></div>
        @endif

        <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" method="POST">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="form-group">
                <label>Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
            </div>
            @if(!isset($user))
            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            @endif
            <div class="form-group">
                <label>Mật khẩu {{ isset($user) ? '(để trống nếu không đổi)' : '' }} <span class="text-danger">{{ isset($user) ? '' : '*' }}</span></label>
                <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
        </form>
    </div>
</div>
@endsection
