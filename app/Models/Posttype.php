<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posttype extends Model
{
    use HasFactory;
    public $guarded = [];

    public function posts()
    {   
        return $this->belongsToMany(Post::class, 'posttypes_posts', 'posttypes_id', 'posts_id');
    }
}
