<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsArticle;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $hotProducts = Product::hot()->latest()->get();
        $hotNews = NewsArticle::hot()->latest()->take(4)->get();
        $categories = Category::homepage()->with('products', 'children.products')->get();

        return view('frontend.home', compact('hotProducts', 'hotNews', 'categories'));
    }
}
