@extends('admin.layouts.app')
@section('title', 'Quản lý tin tức')

@section('content')
<div class="card">
    <div class="card-header">
        Danh sách tin tức
        <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-success">+ Thêm mới</a>
    </div>
    <div class="card-body" style="padding:0;">
        <table class="table table-hover" style="margin:0;">
            <thead><tr><th>#</th><th>Ảnh</th><th>Tiêu đề</th><th>Hot</th><th>Ngày tạo</th><th>Thao tác</th></tr></thead>
            <tbody>
                @forelse($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td><img src="{{ $article->photo_url }}" style="height:45px;width:60px;object-fit:cover;border-radius:4px;"></td>
                    <td>{{ Str::limit($article->name, 60) }}</td>
                    <td>{!! $article->hot ? '<span class="label label-danger">HOT</span>' : '—' !!}</td>
                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.news.edit', $article->id) }}" class="btn btn-xs btn-warning">Sửa</a>
                        <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Xóa bài viết này?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Chưa có tin tức nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $articles->links() }}</div>
</div>
@endsection
