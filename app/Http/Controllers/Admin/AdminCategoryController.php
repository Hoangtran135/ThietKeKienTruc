<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::root()->with('children')->latest()->paginate(25);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::root()->get();

        return view('admin.categories.form', compact('parents'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create([
            'name'            => $request->name,
            'parent_id'       => $request->integer('parent_id', 0),
            'displayhomepage' => $request->boolean('displayhomepage'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        $parents  = Category::root()->where('id', '!=', $id)->get();

        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, int $id)
    {
        Category::findOrFail($id)->update([
            'name'            => $request->name,
            'parent_id'       => $request->integer('parent_id', 0),
            'displayhomepage' => $request->boolean('displayhomepage'),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(int $id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    }
}
