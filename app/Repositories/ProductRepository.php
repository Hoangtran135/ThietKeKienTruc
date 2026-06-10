<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function allWithCategory(int $perPage = 25): LengthAwarePaginator
    {
        return Product::with('category')->latest()->paginate($perPage);
    }

    public function findWithRelations(int $id): Product
    {
        return Product::with(['ratings', 'category'])->findOrFail($id);
    }

    public function categoriesForForm(): \Illuminate\Database\Eloquent\Collection
    {
        return Category::root()->with('children')->get();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): void
    {
        $product->update($data);
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
