<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $keyword   = $request->get('keyword', '');
        $fromPrice = $request->filled('fromPrice') ? (float) $request->get('fromPrice') : null;
        $toPrice   = $request->filled('toPrice') ? (float) $request->get('toPrice') : null;

        $products = $this->productService->search($keyword, $fromPrice, $toPrice);

        // AJAX live search — trả về JSON
        if ($request->expectsJson()) {
            return response()->json([
                'results' => $products->take(8)->map(fn($p) => [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'final_price' => number_format($p->final_price) . '₫',
                    'photo_url'   => $p->photo_url,
                    'url'         => route('products.detail', $p->id),
                ]),
                'total' => $products->count(),
            ]);
        }

        return view('frontend.search', compact('products', 'keyword', 'fromPrice', 'toPrice'));
    }
}
