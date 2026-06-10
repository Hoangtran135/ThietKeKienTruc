<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;

class NewsController extends Controller
{
    public function index()
    {
        $articles = NewsArticle::latest()->paginate(9);

        return view('frontend.news.index', compact('articles'));
    }

    public function detail(int $id)
    {
        $article = NewsArticle::findOrFail($id);
        $related = NewsArticle::where('id', '!=', $id)->latest()->take(4)->get();

        return view('frontend.news.detail', compact('article', 'related'));
    }
}
