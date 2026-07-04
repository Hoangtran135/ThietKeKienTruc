<?php

namespace App\Providers;

use App\Models\NewsArticle;
use App\Models\Product;
use App\Observers\NewsArticleObserver;
use App\Observers\ProductObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Product::observe(ProductObserver::class);
        NewsArticle::observe(NewsArticleObserver::class);

    }
}
