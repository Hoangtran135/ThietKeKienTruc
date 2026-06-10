<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Rating;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $categoryId = $request->integer('category_id') ?: null;
        $order      = $request->get('order', 'newest');

        $products = $this->productService->listByCategory($categoryId, $order);
        $category = $categoryId ? Category::find($categoryId) : null;

        return view('frontend.products.index', compact('products', 'category', 'order', 'categoryId'));
    }

    public function detail(int $id)
    {
        $product   = Product::with(['ratings', 'category'])->findOrFail($id);
        $avgRating = $product->ratings->avg('star') ?? 0;
        $related   = Product::where('category_id', $product->category_id)
                             ->where('id', '!=', $id)
                             ->latest()
                             ->take(6)
                             ->get();

        return view('frontend.products.detail', compact('product', 'avgRating', 'related'));
    }

    public function rating(RatingRequest $request, int $id)
    {
        Rating::create(['product_id' => $id, 'star' => $request->star]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá!');
    }
}
