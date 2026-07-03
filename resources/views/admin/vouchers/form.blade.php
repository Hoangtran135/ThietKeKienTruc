@extends('admin.layouts.app')
@section('title', $voucher->id ? 'Sửa Voucher' : 'Thêm Voucher')

@section('content')
<div class="card" style="max-width:680px;">
    <div class="card-header">
        {{ $voucher->id ? 'Sửa Voucher: ' . $voucher->code : 'Thêm Voucher mới' }}
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:20px;">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $voucher->id ? route('admin.vouchers.update', $voucher->id) : route('admin.vouchers.store') }}" method="POST">
            @csrf
            @if($voucher->id) @method('PUT') @endif

            <div class="form-group">
                <label>Mã voucher <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}"
                       placeholder="VD: SALE20, FREESHIP..." style="text-transform:uppercase;" required>
                <small class="text-muted">Tự động chuyển thành chữ hoa.</small>
            </div>

            <div class="form-group">
                <label>Loại giảm giá <span class="text-danger">*</span></label>
                <select name="type" class="form-control" id="voucherType" required>
                    <option value="percent"  {{ old('type', $voucher->type) === 'percent'  ? 'selected' : '' }}>Giảm theo % tổng đơn</option>
                    <option value="fixed"    {{ old('type', $voucher->type) === 'fixed'    ? 'selected' : '' }}>Giảm số tiền cố định</option>
                    <option value="freeship" {{ old('type', $voucher->type) === 'freeship' ? 'selected' : '' }}>Miễn phí vận chuyển</option>
                </select>
            </div>

            <div class="form-group" id="valueGroup">
                <label>Giá trị <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="number" name="value" class="form-control" value="{{ old('value', $voucher->value) }}" min="0">
                    <span class="input-group-addon" id="valueUnit">%</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Giá trị đơn tối thiểu (₫)</label>
                        <input type="number" name="min_order" class="form-control" value="{{ old('min_order', $voucher->min_order) }}" min="0" placeholder="0 = không giới hạn">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" id="maxDiscountGroup">
                        <label>Giảm tối đa (₫) — chỉ cho loại %</label>
                        <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $voucher->max_discount) }}" min="0" placeholder="Để trống = không giới hạn">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Giới hạn số lượt dùng</label>
                        <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1" placeholder="Để trống = không giới hạn">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Hạn sử dụng</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at', $voucher->expires_at?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}>
                    Kích hoạt voucher
                </label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-default">Hủy</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const typeSelect   = document.getElementById('voucherType');
const valueGroup   = document.getElementById('valueGroup');
const valueUnit    = document.getElementById('valueUnit');
const maxGroup     = document.getElementById('maxDiscountGroup');

function updateForm() {
    const t = typeSelect.value;
    if (t === 'freeship') {
        valueGroup.style.display = 'none';
        maxGroup.style.display   = 'none';
    } else if (t === 'percent') {
        valueGroup.style.display = '';
        maxGroup.style.display   = '';
        valueUnit.textContent    = '%';
    } else {
        valueGroup.style.display = '';
        maxGroup.style.display   = 'none';
        valueUnit.textContent    = '₫';
    }
}

typeSelect.addEventListener('change', updateForm);
updateForm();
</script>
@endpush
@endsection
