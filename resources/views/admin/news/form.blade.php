@extends('admin.layouts.app')
@section('title', isset($article) ? 'Sửa tin tức' : 'Thêm tin tức')

@section('content')
<div class="card">
    <div class="card-header">
        {{ isset($article) ? 'Sửa bài viết' : 'Thêm bài viết mới' }}
        <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-default">← Quay lại</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul style="margin:0;padding-left:15px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul></div>
        @endif

        <form action="{{ isset($article) ? route('admin.news.update', $article->id) : route('admin.news.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($article)) @method('PUT') @endif

            <div class="form-group">
                <label>Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $article->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $article->description ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Nội dung</label>
                <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content', $article->content ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ảnh đại diện</label>
                        @if(isset($article) && $article->photo)
                            <div style="margin-bottom:8px;">
                                <img src="{{ $article->photo_url }}" style="height:80px;object-fit:cover;border-radius:4px;">
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" style="margin-top:25px;">
                        <label><input type="checkbox" name="hot" value="1"
                            {{ old('hot', $article->hot ?? false) ? 'checked' : '' }}> Đánh dấu Hot
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">{{ isset($article) ? 'Cập nhật' : 'Thêm mới' }}</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script>
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('editor');
    }
</script>
@endpush
