<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SiteMap extends Controller
{
    public function generate()
    {
        // Fetch all posts to include in the sitemap
        $posts = Post::select('slug', 'updated_at')->where('status', 1)->latest()->get();

        // Load the sitemap blade view with the posts data
        $xmlcontent =  View::make('sitemap', compact('posts'));

        //set the response type to xml
        $response = Response::make($xmlcontent, 200);
        $response->header('Content-Type', 'text/xml');

        return $response;
    }
}
