<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'content', 'hot',
        'photo', 'price', 'discount', 'category_id', 'stock',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'discount' => 'integer',
        'hot'      => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Giá sau khi giảm
    public function getFinalPriceAttribute(): float
    {
        return $this->price - ($this->price * $this->discount / 100);
    }

    // URL ảnh
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('uploads/products/' . $this->photo)
            : asset('images/no-image.png');
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getStockLabelAttribute(): string
    {
        if ($this->stock <= 0)  return '<span class="badge badge-danger">Hết hàng</span>';
        if ($this->stock <= 10) return '<span class="badge badge-warning">Còn ' . $this->stock . ' sản phẩm</span>';
        return '<span class="badge badge-success">Còn hàng</span>';
    }

    public function scopeHot($query)
    {
        return $query->where('hot', 1);
    }

    public function scopeInCategory($query, int $categoryId)
    {
        $ids = Category::where('id', $categoryId)
            ->orWhere('parent_id', $categoryId)
            ->pluck('id');

        return $query->whereIn('category_id', $ids);
    }
}
