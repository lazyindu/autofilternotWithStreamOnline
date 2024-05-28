<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    public $guarded = [];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'genres_posts', 'genres_id', 'posts_id');
    }
}
