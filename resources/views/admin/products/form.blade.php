@extends('admin.layouts.app')
@section('title', isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        {{ isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' }}
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-default">← Quay lại</a>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul style="margin:0;padding-left:15px;">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul></div>
        @endif

        <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($product)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả ngắn</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Nội dung chi tiết</label>
                        <textarea name="content" id="editor" class="form-control" rows="8">{{ old('content', $product->content ?? '') }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id', $product->category_id ?? '') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;— {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá (₫) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? 0) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Giảm giá (%)</label>
                        <input type="number" name="discount" class="form-control" value="{{ old('discount', $product->discount ?? 0) }}" min="0" max="100">
                    </div>
                    <div class="form-group">
                        <label>Ảnh sản phẩm</label>
                        @if(isset($product) && $product->photo)
                            <div style="margin-bottom:8px;">
                                <img src="{{ $product->photo_url }}" style="height:100px;object-fit:contain;">
                            </div>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="hot" value="1" {{ old('hot', $product->hot ?? false) ? 'checked' : '' }}> Sản phẩm HOT</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                {{ isset($product) ? 'Cập nhật' : 'Thêm mới' }}
            </button>
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
