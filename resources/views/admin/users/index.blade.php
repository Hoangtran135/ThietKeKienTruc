@extends('admin.layouts.app')
@section('title', 'Quản trị viên')

@section('content')
<div class="card">
    <div class="card-header">
        Danh sách quản trị viên
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success">+ Thêm mới</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Tên</th><th>Email</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Xóa tài khoản này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Chưa có tài khoản nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $users->links() }}</div>
</div>
@endsection
