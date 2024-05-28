<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;
    public $guarded = [];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'qualities_posts', 'qualities_id', 'posts_id');

    }
}
