<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['parent_id', 'name', 'displayhomepage'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeRoot($query)
    {
        return $query->where('parent_id', 0);
    }

    public function scopeHomepage($query)
    {
        return $query->where('displayhomepage', 1);
    }
}
