<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = Admin::latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(StoreAdminUserRequest $request)
    {
        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm quản trị viên thành công!');
    }

    public function edit(int $id)
    {
        $user = Admin::findOrFail($id);

        return view('admin.users.form', compact('user'));
    }

    public function update(UpdateAdminUserRequest $request, int $id)
    {
        $user       = Admin::findOrFail($id);
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy(int $id)
    {
        Admin::findOrFail($id)->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa quản trị viên!');
    }
}
