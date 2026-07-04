<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(15);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.form', ['voucher' => new Voucher]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'type' => 'required|in:percent,fixed,freeship',
            'value' => 'required_unless:type,freeship|integer|min:0',
            'min_order' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công!');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.form', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,'.$voucher->id,
            'type' => 'required|in:percent,fixed,freeship',
            'value' => 'required_unless:type,freeship|integer|min:0',
            'min_order' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return back()->with('success', 'Đã xoá voucher.');
    }
}
