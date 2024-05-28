<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    public $guarded = [];

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genres_posts', 'posts_id', 'genres_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'languages_posts', 'posts_id', 'languages_id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_posts', 'posts_id', 'categories_id');
    }
    public function qualities()
    {
        return $this->belongsToMany(Quality::class, 'qualities_posts', 'posts_id', 'qualities_id');
    }

    public function posttypes()
    {
        return $this->belongsToMany(Posttype::class, 'posttypes_posts', 'posts_id', 'posttypes_id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
