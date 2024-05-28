<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $guarded = [];
    
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'categories_posts', 'categories_id', 'posts_id');
    }
}
