@extends('admin.layouts.app')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        Danh sách sản phẩm
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success">+ Thêm mới</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Ảnh</th><th>Tên sản phẩm</th><th>Giá</th><th>Danh mục</th><th>Tồn kho</th><th>Hot</th><th>Thao tác</th></tr></thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ $product->photo_url }}" style="height:50px;width:50px;object-fit:contain;"></td>
                    <td>{{ Str::limit($product->name, 50) }}</td>
                    <td>{{ number_format($product->price) }}₫</td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>
                        @if($product->stock <= 0)
                            <span class="label label-danger">Hết hàng</span>
                        @elseif($product->stock <= 10)
                            <span class="label label-warning">{{ $product->stock }}</span>
                        @else
                            <span class="label label-success">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>{!! $product->hot ? '<span class="label label-danger">HOT</span>' : '—' !!}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Chưa có sản phẩm nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $products->links() }}</div>
</div>
@endsection
