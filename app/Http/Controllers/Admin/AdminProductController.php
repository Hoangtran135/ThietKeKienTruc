<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Repositories\ProductRepository;
use App\Services\ImageUploadService;

class AdminProductController extends Controller
{
    public function __construct(
        private ProductRepository $productRepo,
        private ImageUploadService $imageService,
    ) {}

    public function index()
    {
        $products = $this->productRepo->allWithCategory();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->productRepo->categoriesForForm();

        return view('admin.products.form', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['hot'] = $request->boolean('hot');
        $data['discount'] = $data['discount'] ?? 0;

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->upload($request->file('photo'), 'products');
        }

        $this->productRepo->create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(int $id)
    {
        $product = $this->productRepo->findWithRelations($id);
        $categories = $this->productRepo->categoriesForForm();

        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, int $id)
    {
        $product = $this->productRepo->findWithRelations($id);
        $data = $request->validated();
        $data['hot'] = $request->boolean('hot');
        $data['discount'] = $data['discount'] ?? 0;

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                $this->imageService->delete('products', $product->photo);
            }
            $data['photo'] = $this->imageService->upload($request->file('photo'), 'products');
        }

        $this->productRepo->update($product, $data);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(int $id)
    {

        $this->productRepo->delete($this->productRepo->findWithRelations($id));

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm!');
    }
}
