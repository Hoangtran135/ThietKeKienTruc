@extends('admin.layouts.app')
@section('title', 'Quản lý danh mục')

@section('content')
<div class="card">
    <div class="card-header">
        Danh mục sản phẩm
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-success">+ Thêm mới</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Tên danh mục</th><th>Danh mục con</th><th>Hiển thị trang chủ</th><th>Thao tác</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td>
                        @foreach($cat->children as $child)
                            <span class="label label-default">{{ $child->name }}</span>
                        @endforeach
                    </td>
                    <td>{!! $cat->displayhomepage ? '<span class="label label-success">Có</span>' : '—' !!}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Xóa danh mục này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Chưa có danh mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $categories->links() }}</div>
</div>
@endsection
