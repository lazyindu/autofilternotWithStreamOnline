<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class HomeController extends Controller
{

    // function getShortLink($link) {
    //     $https = explode(':', $link)[0];
    //     if ($https === 'http') {
    //         $https = 'https';
    //         $link = str_replace('http', $https, $link);
    //     }

    //     $url = 'https://' . env('URL_SHORTENR_WEBSITE') . '/api';
    //     $params = [
    //         'api' => env('URL_SHORTNER_WEBSITE_API'),
    //         'url' => $link,
    //     ];

    //     try {
    //         $response = Http::get($url, $params);
    //         $data = $response->json();
    //         if ($data['status'] === 'success') {
    //             return $data['shortenedUrl'];
    //         } else {
    //             logger()->error("Error: " . $data['message']);
    //             return 'https://' . env('URL_SHORTENR_WEBSITE') . '/api?api=' . env('URL_SHORTNER_WEBSITE_API') . '&link=' . $link;
    //         }
    //     } catch (\Exception $e) {
    //         logger()->error($e);
    //         return env('URL_SHORTENR_WEBSITE') . '/api?api=' . env('URL_SHORTNER_WEBSITE_API') . '&link=' . $link;
    //     }
    // }

    // another code
    // $long_url = urlencode('https://facebook.com');
    // $api_token = '72a7f0131e5e657e37cf7e2a9e928a616b671cf5';

    // $url = urlencode('https://google.com');
    // $json = file_get_contents("https://atglinks.com/api?api={$api_token}&url={$long_url}&alias=CustomAlias");
    // $data = json_decode ($json, true);
    // dd($data);

    public function index (Request $request)
    {

        // $postData = Post::with('categories')->orderBy('created_at', 'desc')->paginate(10);
        $query = $request->input('query');

        if(empty($query)){
            $postData = Post::where('status', 1)->orderBy('updated_at', 'desc')->paginate(30);
            // $long_url = urlencode('https://moviesadda.pro');
            // $api_token = '72a7f0131e5e657e37cf7e2a9e928a616b671cf5';

            // $url = urlencode('https://moviesadda.pro');
            // $json = file_get_contents("https://atglinks.com/api?api={$api_token}&url={$long_url}");
            // $data = json_decode ($json, true);
            // dd($data);
        }else{
            $postData = Post::where('status', 1)
                    ->where( function ($queryBuilder) use ($query) {
                        $queryBuilder->where('title', 'like', '%' . $query . '%')
                                     ->orWhere('movie_name', 'like', '%' . $query . '%')
                                     ->orWhere('release_year', 'like', '%' . $query . '%')
                                     ->orWhere('format', 'like', '%' . $query . '%')
                                     ->orWhere('pixels', 'like', '%' . $query . '%')
                                     ->orWhere('min_size', 'like', '%' . $query . '%')
                                     ->orWhere('med_size', 'like', '%' . $query . '%')
                                     ->orWhere('max_size', 'like', '%' . $query . '%');
                    })->orderBy('created_at', 'desc')->paginate(20);

        }
        return view('homedashboard.homescreen', compact('postData', 'query'));
    }

    public function noInternet(){
        return view('errors.no_internet');
    }
    
    public function filterByCategory($categoryName)
    {
        $query = $categoryName;
        $data = Category::where('category_name', $categoryName)->first();

        // If category does not exist, return back
        if (!$data) {
            return redirect()->route('home')->with('alert', '⚠ Category not found ⚠');
        }else{
            $postData = $data->posts()->where('status', 1)->orderBy('created_at', 'desc')->paginate(20);
            return view('homedashboard.homescreen', compact('postData', 'query'));
        }

    }
    
    public function masterPage($slug)
    {
        $data = Post::where('slug', $slug)->first();
        
        if ($data) {
            if ($data->download_page) {
                $url_data = $data->download_page;
            } else {
                $url_data = '';
            }
    
            $app_url = url('/');
            $direct_url = "{$app_url}/download-links/{$url_data}";
            $download_page_url = $direct_url;
            if (env('URL_MODE')) {
                $long_url = urlencode($direct_url);
                $shortner_website = env('URL_SHORTENR_WEBSITE');
                $api_token = env('URL_SHORTNER_WEBSITE_API');
                $api_url = "https://{$shortner_website}/api?api={$api_token}&url={$long_url}";
                
                // Initialize cURL session
                $ch = curl_init();
                
                // Set cURL options
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                // Execute cURL request
                $json = curl_exec($ch);
                
                // Close cURL session
                curl_close($ch);
                // dd($json);
                if ($json !== false) {
                    // Attempt to decode JSON response
                    $shortData = json_decode($json, true);
                    // dd($shortData);
    
                    if ($shortData !== null && isset($shortData['shortenedUrl'])) {
                        $download_page_url = $shortData['shortenedUrl'];
                    } else {
                        // Handle case where shortened URL is not available or JSON structure is unexpected
                        // Log::error("Shortened URL data is invalid or missing 'shortenedUrl' key");
                    }
                } else {
                    // Handle case where cURL request fails
                    // Log::error("Failed to fetch data from URL shortening service");
                }
            }
            //  dd($download_page_url);
    
            return view('homedashboard.masterPage', compact('data', 'download_page_url'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    
    // public function masterPage ($slug)
    // {
    //     $data = Post::where('slug', $slug)->first();
        
    //     if ($data) {
    //         if($data->download_page){
    //             $url_data = $data['download_page'];
    //         }else{
    //             $url_data = '';
    //         }
    //         // $url_data = $data['download_page'];
             
    //         $app_url = url('/');
    //         $direct_url = "https://{$app_url}/download-links/{$url_data}";
    //         // $direct_url = 'https://moviesadda.pro/download-links/download-links-for-amar-singh-chamkila-480p-720p-1080p';
    //         // dd($direct_url); 
    //         $download_page_url = $direct_url;
    //         if(env('URL_MODE')){
    //             // $long_url = urlencode($url_data);
    //             // $shortner_website = env('URL_SHORTENR_WEBSITE');
    //             // $api_token = env('URL_SHORTNER_WEBSITE_API');
    //             // $api_url = "https://{$shortner_website}/api?api={$api_token}&url={$long_url}";
    //             // $json = @file_get_contents("https://{$shortner_website}/api?api={$api_token}&url={$long_url}");
    //             // $shortData = @json_decode ($json, true);
    //             // // $shortData = @json_decode(file_get_contents($api_url),TRUE);
    //             // dd($json);
    //             // $download_page_url = $shortData['shortenedUrl'];
    //         }else{
    //             $download_page_url = $direct_url;
    //         }
    //         // dd($download_page_url);
            
    //         return view('homedashboard.masterPage', compact('data', 'download_page_url'));
    //     } else {
    //         abort(Response::HTTP_NOT_FOUND);
    //     }

    // }
    
    public function downloadPage ($url)
    {
        $data = Page::where('slug', $url)->first();

        if ($data) {
                return view('homedashboard.page', compact('data'));
            } else {
                abort(Response::HTTP_NOT_FOUND);
            }
    }

    public function addComment(Request $request, $postId)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required', 
            'dp' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
            'comment' => 'required',
        ]);

        if($request->hasFile('dp')){
            $imageName = time() . '.' . $request->dp->getClientOriginalExtension();
            $request->dp->move(public_path('/profile_pic'), $imageName);
            $data['dp']=$imageName;
        };

        try {
            $post = Post::findOrFail($postId); 
            $post->comments()->create($data);
        
            return redirect()->back()->with('success', 'Your comment has been added successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'The associated post was not found.');
        }

    }

    public function aboutUs()
    {
        return view('homedashboard.extras.aboutUs');
    }
    public function contactUs()
    {
        return view('homedashboard.extras.contactUs');
    }
    public function disclaimer()
    {
        return view('homedashboard.extras.disclaimer');
    }
    public function dmca()
    {
        return view('homedashboard.extras.dmca');
    }
    public function privacyPolicy()
    {
        return view('homedashboard.extras.privacyPolicy');
    }
    public function requestUs()
    {
        return view('homedashboard.extras.requestUs');
    }
    public function termsOfUse()
    {
        return view('homedashboard.extras.termsOfUse');
    }
}
