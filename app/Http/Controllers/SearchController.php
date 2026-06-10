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

        return view('frontend.search', compact('products', 'keyword', 'fromPrice', 'toPrice'));
    }
}
