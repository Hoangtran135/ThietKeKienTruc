@extends('admin.layouts.app')
@section('title', 'Quản lý Voucher')

@section('content')
<div class="card">
    <div class="card-header">
        Danh sách Voucher
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-sm btn-success">+ Thêm mới</a>
    </div>
    <div class="card-body" style="padding:0;">
        @if(session('success'))
            <div class="alert alert-success" style="margin:15px;">{{ session('success') }}</div>
        @endif
        <table class="table table-hover" style="margin:0;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mã</th>
                    <th>Loại giảm</th>
                    <th>Đơn tối thiểu</th>
                    <th>Hạn dùng</th>
                    <th>Đã dùng</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->id }}</td>
                    <td><strong style="font-family:monospace;font-size:14px;color:#c0392b;">{{ $voucher->code }}</strong></td>
                    <td>{{ $voucher->type_label }}</td>
                    <td>{{ $voucher->min_order > 0 ? number_format($voucher->min_order) . '₫' : '—' }}</td>
                    <td>{{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y') : 'Không hạn' }}</td>
                    <td>
                        {{ $voucher->used_count }}
                        @if($voucher->usage_limit)
                            / {{ $voucher->usage_limit }}
                        @endif
                    </td>
                    <td>
                        @if($voucher->isValid())
                            <span class="label label-success">Hoạt động</span>
                        @else
                            <span class="label label-default">Hết hạn / Tắt</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                        <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Xoá voucher này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Chưa có voucher nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $vouchers->links() }}</div>
</div>
@endsection
