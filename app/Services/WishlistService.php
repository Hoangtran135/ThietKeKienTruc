<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    public function get(): array
    {
        return Session::get('wishlist', []);
    }

    public function add(int $productId): void
    {
        $wishlist = $this->get();

        if (!isset($wishlist[$productId])) {
            $product              = Product::findOrFail($productId);
            $wishlist[$productId] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'photo' => $product->photo,
                'price' => $product->final_price,
            ];
            Session::put('wishlist', $wishlist);
        }
    }

    public function remove(int $productId): void
    {
        $wishlist = $this->get();
        unset($wishlist[$productId]);
        Session::put('wishlist', $wishlist);
    }
}
