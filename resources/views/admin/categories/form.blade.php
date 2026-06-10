@extends('admin.layouts.app')
@section('title', isset($category) ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        {{ isset($category) ? 'Sửa danh mục' : 'Thêm danh mục mới' }}
        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-default">← Quay lại</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form action="{{ isset($category) ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}" method="POST">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            <div class="form-group">
                <label>Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label>Danh mục cha</label>
                <select name="parent_id" class="form-control">
                    <option value="0">-- Là danh mục cấp 1 --</option>
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id ?? 0) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="displayhomepage" value="1"
                    {{ old('displayhomepage', $category->displayhomepage ?? false) ? 'checked' : '' }}>
                    Hiển thị trên trang chủ
                </label>
            </div>
            <button type="submit" class="btn btn-success">
                {{ isset($category) ? 'Cập nhật' : 'Thêm mới' }}
            </button>
        </form>
    </div>
</div>
@endsection
