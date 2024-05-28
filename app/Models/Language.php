<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    public $guarded = [];
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'languages_posts', 'languages_id', 'posts_id');

    }
}
