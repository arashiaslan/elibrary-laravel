<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['category_id', 'title', 'slug', 'author', 'publisher', 'stock', 'cover_image', 'synopsis'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
