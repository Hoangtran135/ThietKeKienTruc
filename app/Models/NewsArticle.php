<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsArticle extends Model
{
    protected $table = 'news';

    protected $fillable = ['name', 'description', 'content', 'hot', 'photo'];

    protected $casts = ['hot' => 'boolean'];

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('uploads/news/' . $this->photo)
            : asset('images/no-image.png');
    }

    public function scopeHot($query)
    {
        return $query->where('hot', 1);
    }
}
