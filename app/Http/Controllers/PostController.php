<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Post;
use App\Models\Posttype;
use App\Models\Quality;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class PostController extends Controller
{
    /**
     * Admin Controls.
     */


//     public function mount()
//     {
//     //   $this->data = CodeBlog::get();
//     if(empty($this->query)){
//         $this->data = [];
//    }else{
//         $this->data = CodeBlog::where('title', 'like', '%' . $this->query . '%')
//         ->orWhere('short_desc', 'like', '%' . $this->query . '%')
//         ->orWhere('description', 'like', '%' . $this->query . '%')
//         ->orWhere('meta_title', 'like', '%' . $this->query . '%')
//         ->orWhere('meta_keywords', 'like', '%' . $this->query . '%')
//         ->get();
//    }
//     }

            // public function getQuery(Request $request)
            // {   
            //     $query = $request->input('query');
                
            //     if(empty($query)){
            //         $data = Post::where('status', 1)->paginate(20);
            //     }else{
            //         $data = Post::where('status', 1)
            //                 ->where( function ($queryBuilder) use ($query) {
            //                     $queryBuilder->where('title', 'like', '%' . $query . '%')
            //                                  ->orWhere('movie_name', 'like', '%' . $query . '%')
            //                                  ->orWhere('release_year', 'like', '%' . $query . '%')
            //                                  ->orWhere('format', 'like', '%' . $query . '%')
            //                                  ->orWhere('pixels', 'like', '%' . $query . '%')
            //                                  ->orWhere('min_size', 'like', '%' . $query . '%')
            //                                  ->orWhere('med_size', 'like', '%' . $query . '%')
            //                                  ->orWhere('max_size', 'like', '%' . $query . '%')
            //                                  ->orWhere('author', 'like', '%' . $query . '%');
            //                 })->paginate(20);
            //     }

            //     return view('admin.posts.managePost', compact('data'));

            // }


    public function allPosts(Request $request) 
    {
        $query = $request->input('query');
                
        if(empty($query)){
            $posts = Post::where('status', 1)->orderBy('updated_at', 'desc')->paginate(20);
        }else{
            $posts = Post::where('status', 1)
                    ->where( function ($queryBuilder) use ($query) {
                        $queryBuilder->where('title', 'like', '%' . $query . '%')
                                     ->orWhere('movie_name', 'like', '%' . $query . '%')
                                     ->orWhere('release_year', 'like', '%' . $query . '%')
                                     ->orWhere('format', 'like', '%' . $query . '%')
                                     ->orWhere('pixels', 'like', '%' . $query . '%')
                                     ->orWhere('min_size', 'like', '%' . $query . '%')
                                     ->orWhere('med_size', 'like', '%' . $query . '%')
                                     ->orWhere('max_size', 'like', '%' . $query . '%')
                                     ->orWhere('author', 'like', '%' . $query . '%');
                    })->orderBy('created_at', 'desc')->paginate(20);
        }
        // $posts = Post::with('categories')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.posts.allPost', compact('posts'));
    }
    
    public function allDrafts(Request $request) 
    {
        $query = $request->input('query');
                
        if(empty($query)){
            $posts = Post::where('status', 0)->orderBy('updated_at', 'desc')->paginate(20);
        }else{
            $posts = Post::where('status', 0)
                    ->where( function ($queryBuilder) use ($query) {
                        $queryBuilder->where('title', 'like', '%' . $query . '%')
                                     ->orWhere('movie_name', 'like', '%' . $query . '%')
                                     ->orWhere('release_year', 'like', '%' . $query . '%')
                                     ->orWhere('format', 'like', '%' . $query . '%')
                                     ->orWhere('pixels', 'like', '%' . $query . '%')
                                     ->orWhere('min_size', 'like', '%' . $query . '%')
                                     ->orWhere('med_size', 'like', '%' . $query . '%')
                                     ->orWhere('max_size', 'like', '%' . $query . '%')
                                     ->orWhere('author', 'like', '%' . $query . '%');
                    })->orderBy('created_at', 'desc')->paginate(20);
        }
        // $posts = Post::with('categories')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.posts.allDrafts', compact('posts'));
    }
    public function allDeletedPosts(Request $request) 
    { 
        $user = Auth::user();
        if($user->role == 'admin'){
            $query = $request->input('query');
                 
            if(empty($query)){
                $posts = Post::where('status', 2)->orderBy('updated_at', 'desc')->paginate(20);
            }else{
                $posts = Post::where('status', 2)
                        ->where( function ($queryBuilder) use ($query) {
                            $queryBuilder->where('title', 'like', '%' . $query . '%')
                                        ->orWhere('movie_name', 'like', '%' . $query . '%')
                                        ->orWhere('release_year', 'like', '%' . $query . '%')
                                        ->orWhere('format', 'like', '%' . $query . '%')
                                        ->orWhere('pixels', 'like', '%' . $query . '%')
                                        ->orWhere('min_size', 'like', '%' . $query . '%')
                                        ->orWhere('med_size', 'like', '%' . $query . '%')
                                        ->orWhere('max_size', 'like', '%' . $query . '%')
                                        ->orWhere('author', 'like', '%' . $query . '%');
                        })->orderBy('updated_at', 'desc')->paginate(20);
            }
            // $posts = Post::with('categories')->orderBy('created_at', 'desc')->paginate(20);

            return view('admin.trash.deleted_post', compact('posts'));
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }
    }



    public function createPost() 
    {
        $all_categories = Category::where('status', 1)->get();
        $languages = Language::where('status', 1)->get();
        $genres = Genre::where('status', 1)->get();
        $qualities = Quality::where('status', 1)->get();
        $postTypes = Posttype::where('status', 1)->get();
        return view('admin.posts.createPost', compact('all_categories','languages','genres','qualities', "postTypes"));

    }
    public function createNewPost(Request $request)
    {  
        $user = Auth::user();
        
       $data =  $request->validate([
            // general
            'thumbnail' => 'required|image|mimes:jpeg,png,gif,svg',
            'title' => 'required',
            'pixels' => 'nullable',
            'format' => 'nullable',
            
            // sizes
            'min_size' => 'nullable', // Minimum size of the file
            'med_size' => 'nullable', // Medium size of the file
            'max_size' => 'nullable', // Maximum size of the file
            
            // user's choice
            'is_highly_requested' => 'nullable',
            'is_most_searched' => 'nullable',
            'is_newly_released' => 'nullable',
            
            // imdb
            'rating' => 'nullable', // Input rating from imdb
            'movie_name' => 'nullable', // Movie's full name 
            'release_year' => 'nullable', // Movie's release year
            
            // about 
            'storyline' => 'nullable', // Movie's storyline from IMDB
            'screenshots' => 'nullable', // Upload movie's screenshots
            'download_description' => 'required', // Download links with text and buttons 
            
            // for search console 
            'meta_title' => '', // Title to be indexed on google search console
            'meta_description' => 'nullable', // Description to be indexed on google search console
            'meta_keywords' => 'nullable', // Keywords to be indexed on google search console
            
            // draft 
            'status' => '0', // By default post will be set as draft // Admin and Manager can recheck the post and publich it !

            // page
            "download_page" => 'nullable'
        ]);
        
        // $inp = $request->validate([
        //     'genres' => 'required|array',
        //     'qualities' => 'required|array'
        //     ]);

        // dd($data['post_type']);
        // for thumbnail 
        if($request->hasFile('thumbnail')){
            $imageName = time() . '.' . $request->thumbnail->getClientOriginalExtension();
            $request->thumbnail->move(public_path('/thumbnails'), $imageName);
            $data['thumbnail']=$imageName;
        }

// // CK EDITOR IMAGE
        if($data['screenshots']){
            $description = $data['screenshots'];

            $dom = new \DomDocument();
            libxml_use_internal_errors(true); // Suppress any potential HTML parsing errors
            $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
            $images = $dom->getElementsByTagName('img');
    
            foreach ($images as $key => $img) {
                $imageData = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
                $image_name = "/upload/" . time() . $key . '.png';
                file_put_contents(public_path() . $image_name, $imageData);
    
                $img->removeAttribute('src');
                $img->setAttribute('src', $image_name);
            }
    
            $description = $dom->saveHTML();
            $data['screenshots'] = $description;
        }


        // Handle image uploads in the description field
        // $description = $data['screenshots'];
        // $dom = new \DomDocument();
        // libxml_use_internal_errors(true); // Suppress any potential HTML parsing errors
        // $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
        // $images = $dom->getElementsByTagName('img');
    
        // foreach ($images as $index => $image) {
        //     $imageSrc  = $image->getAttribute('src');
        //     list($type, $imageSrc ) = explode(';', $imageSrc );
        //     list(, $imageSrc ) = explode(',', $imageSrc );
        //     $imageData = base64_decode($imageSrc );
    
        //     $image_name = "/upload/" . time() . Str::random(10) . '.png';
        //     Storage::disk('public')->put($image_name, $imageData);
    
        //     $image->removeAttribute('src');
        //     $image->setAttribute('src', asset('storage' . $image_name));
        // }
    
        // $description = $dom->saveHTML();
        // $data['screenshots'] = $description;
        
        // if ($request->hasFile('screenshots')) {
        //     $screenshots = [];
        
        //     foreach ($request->file('screenshots') as $file) {
        //         // Generate a unique file name
        //         $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        //         // Store the file in the 'screenshots' directory
        //         $file->move(public_path('/screenshots'), $imageName);
        //         // Push the new file path to the screenshots array
        //         array_push($screenshots, $imageName);
        //     }
        
        //     // Update the $data array with the screenshots array
        // $data['screenshots'] = -------;
        // }
        
        try
        {
            if($user->role == 'manager')
            {
                $data['author'] = $user->name;
                $data['author_role'] = 'manager';
                $data['manager_id'] = $user->id;
            }
            if($user->role == 'admin')
            {
                $data['author'] = $user->name;
                $data['author_role'] = 'admin';
                $data['admin_id'] = $user->id;
            }
        }catch (ModelNotFoundException $e){
            return redirect()->back()->with('error', 'Something went wrong with the posts author');
        }

        $post = Post::create($data);

        // attaching post with foreign ids !
        $post->genres()->attach($request->input('genres'));
        $post->categories()->attach($request->input('categories'));
        $post->languages()->attach($request->input('languages'));
        $post->qualities()->attach($request->input('qualities'));
        $post->posttypes()->attach($request->input('post_type'));

        return redirect()->back()->with('success', 'Categories inserted successfully!');
    }

    public function updatePostPage($postId)
    {
        try{
            $all_categories = Category::where('status', 1)->get();
            $languages = Language::where('status', 1)->get();
            $genres = Genre::where('status', 1)->get();
            $qualities = Quality::where('status', 1)->get();
            $postTypes = Posttype::where('status', 1)->get();
            
            $data = Post::where('id', $postId)->first();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert' , 'Something went wrong while viewing post');
        }

        return view('admin.posts.updatePost', compact('data','all_categories','languages','genres','qualities', "postTypes"));
    }
    public function updatePost(Request $request, $postId)
    {  
        $user = Auth::user();
        $data =  $request->validate([
            // general
            'thumbnail' => 'nullable|image|mimes:jpeg,png,gif,svg',
            'title' => 'nullable',
            'pixels' => 'nullable',
            'format' => 'nullable',
            
            // sizes
            'min_size' => 'nullable', // Minimum size of the file
            'med_size' => 'nullable', // Medium size of the file
            'max_size' => 'nullable', // Maximum size of the file
            
            // user's choice
            'is_highly_requested' => 'nullable',
            'is_most_searched' => 'nullable',
            'is_newly_released' => 'nullable',
            
            // imdb
            'rating' => 'nullable', // Input rating from imdb
            'movie_name' => 'nullable', // Movie's full name 
            'release_year' => 'nullable', // Movie's release year
            
            // about 
            'storyline' => 'nullable', // Movie's storyline from IMDB
            'screenshots' => 'nullable', // Upload movie's screenshots
            'download_description' => 'required', // Download links with text and buttons 
            
            // for search console 
            'meta_title' => 'nullable', // Title to be indexed on google search console
            'meta_description' => 'nullable', // Description to be indexed on google search console
            'meta_keywords' => 'nullable', // Keywords to be indexed on google search console
            
            // draft 
            'status' => '0', // By default post will be set as draft // Admin and Manager can recheck the post and publich it !
            "download_page" => "nullable",
        ]);

        // dd($data['post_type']);
        // for thumbnail 

        $post = Post::findOrFail($postId);
        
        if($request->hasFile('thumbnail')){
            $existingImage = $post->thumbnail;
            $imagePath = public_path('/thumbnails'.'/'.$existingImage);
            
            // unlinking current image
            if(file_exists($imagePath)){
                unlink($imagePath);

            }
            
            // uploading current image
            $imageName = time() . '.' . $request->thumbnail->getClientOriginalExtension();
            $request->thumbnail->move(public_path('/thumbnails'), $imageName);
            $data['thumbnail']=$imageName;
        }

        // ck editor image
        if($data['screenshots'])
        {
            $description = $data['screenshots'];

            $dom = new \DomDocument();
            libxml_use_internal_errors(true); // Suppress any potential HTML parsing errors
            $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
            $images = $dom->getElementsByTagName('img');
    
            foreach ($images as $key => $img) {
    
                // Check if the image is a new one
                if (strpos($img->getAttribute('src'),'data:image/') ===0) {
                  
                    $imageData = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
                    $image_name = "/upload/" . time(). $key.'.png';
                    file_put_contents(public_path().$image_name,$imageData);
                    
                    $img->removeAttribute('src');
                    $img->setAttribute('src',$image_name);
                }
    
            }
            $description = $dom->saveHTML();
            $data['screenshots'] = $description;
        }

        // if screenshot is deleted
        // if($request->input('deleted_screenshots'))
        // {

        //     $deletedScreenshots = explode(',', $request->input('deleted_screenshots'));
        //     foreach ($deletedScreenshots as $filename) {
        //         // Remove the file from storage
        //         Storage::delete('/screenshots/' . $filename);
    
        //     }
        // }
       
    

    //  if ($request->hasFile('images')) {
    //     $images = [];
    //     foreach ($data['images'] as $image) {
    //         \Storage::delete($project->images);
    //         $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
    //         $image_path =  $image->storeAs('images', $fileName, 'public');
    //         array_push($images, $image_path);
    //         $data['images'] = $images;
    //         $project->update($data);
    //     }
    // }
        // Handle image uploads in the description field 
        // $description = $data['screenshots'];
        // $dom = new \DomDocument();
        // libxml_use_internal_errors(true); // Suppress any potential HTML parsing errors
        // $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // $images = $dom->getElementsByTagName('img');

        // foreach ($images as $index => $image) {
        //     $imageSrc  = $image->getAttribute('src');
        //     list($type, $imageSrc ) = explode(';', $imageSrc );
        //     list($type, $imageSrc ) = explode(',', $imageSrc );
        //     $imageData = base64_decode($imageSrc );

        //     $image_name = "/upload/" . time() . Str::random(10) . '.png';
        //     Storage::disk('public')->put($image_name, $imageData);

        //     $image->removeAttribute('src');
        //     $image->setAttribute('src', asset('storage' . $image_name));
        // }
        // $description = $dom->saveHTML();
        // $data['screenshots'] = $description;
        
        try
        {
           if($user->role == 'manager')
            {
                $data['updated_by'] = $user->name;
                $data['updator_role'] = 'manager';
                $data['updator_id'] = $user->manager_id;
            }
            if($user->role == 'admin')
            {
                $data['updated_by'] = $user->name;
                $data['updator_role'] = 'admin';
                $data['updator_id'] = $user->admin_id;
            }
        }catch (ModelNotFoundException $e){
            return redirect()->back()->with('error', 'Something went wrong with the posts author');
        }
        // $division = Division::where("slug", $this->slug);
        
        $post->update($data);

        $post->slug = Str::slug(ucwords($request->get("title")), "-");
        $post->save();

        $post->genres()->sync($request->input('genres'));
        $post->categories()->sync($request->input('categories'));
        $post->languages()->sync($request->input('languages'));
        $post->qualities()->sync($request->input('qualities'));
        $post->posttypes()->sync($request->input('post_type'));

        return redirect()->back()->with('success', 'Categories Updated successfully!');
    }

    public function managePost(Request $request, $postId)
    {
        $post = Post::where('id', $postId)->first();
        $status = $request->input('type');

        try{
                if($status == 0 )
            {
                $post->update(['status' => $status]);
            }
            if($status == 1 )
            {
                $post->update(['status' => $status]);
            }
            if($status == 2 )
            {
                $post->update(['status' => $status]);
            }
        }catch (ModelNotFoundException $e)
        {
            return redirect()->back()->with('alert', '❌ Something went wrong ❌');
        }
        
        return redirect()->back()->with('alert', 'Status Rolled Back ✔');
    }

    public function delete($id)
    {
        $deletePost = Post::where('id', $id)->delete();
        return redirect()->back()->with('alert', 'Post deleted permanently ✅');
    }

}
