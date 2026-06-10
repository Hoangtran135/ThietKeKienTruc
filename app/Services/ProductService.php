<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function listByCategory(?int $categoryId, string $order): LengthAwarePaginator
    {
        $query = Product::query();

        if ($categoryId) {
            $query->inCategory($categoryId);
        }

        $query = match ($order) {
            'priceAsc'  => $query->orderBy('price', 'asc'),
            'priceDesc' => $query->orderBy('price', 'desc'),
            'nameAsc'   => $query->orderBy('name', 'asc'),
            'nameDesc'  => $query->orderBy('name', 'desc'),
            default     => $query->latest(),
        };

        return $query->paginate(12)->withQueryString();
    }

    public function search(string $keyword, ?float $fromPrice, ?float $toPrice): LengthAwarePaginator
    {
        $query = Product::query();

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($fromPrice !== null) {
            $query->where('price', '>=', $fromPrice);
        }

        if ($toPrice !== null) {
            $query->where('price', '<=', $toPrice);
        }

        return $query->latest()->paginate(12)->withQueryString();
    }
}
