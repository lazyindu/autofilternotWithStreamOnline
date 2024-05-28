<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function allPage(Request $request)
    {
        $query = $request->input('query');
                
        if(empty($query)){
            $page = Page::where('status', 1)->orderBy('created_at', 'desc')->paginate(20);
        }else{
            $page = Page::where('status', 1)
                    ->where( function ($queryBuilder) use ($query) {
                        $queryBuilder->where('title', 'like', '%' . $query . '%')
                                     ->orWhere('description', 'like', '%' . $query . '%')
                                     ->orWhere('author', 'like', '%' . $query . '%');
                    })->orderBy('created_at', 'desc')->paginate(20);
        }

        return view('admin.pages.allPage', compact('page'));
    }

    public function view()
    {
        return view('admin.pages.newPage');
    }

    public function newPage(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            "title" => 'required',
            "for_movie_or_series" => 'required',
            "description" => 'required',
            "status" => '1',
        ]);

        try
        {
            $data['author'] = $user->name;
            $data['author_role'] = $user->role;
            $data['author_id'] = $user->id;

        }catch (ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'Something went wrong with the posts author');
        }

        // create data
        Page::create($data);

        return redirect()->back()->with('alert', 'Page created successfully');
    }

    public function updateView($id)
    {   
        $data = Page::where('id', $id)->first();
        return view('admin.pages.updatePage', compact('data'));
    }

    // public function update(Request $request, $slug)
    // {
    //     $user = Auth::user();
    //     $data = $request->validate([
    //         'title' => 'required',
    //         'description' => 'required'
    //     ]);
        
    //     if($user->role == 'manager')
    //         {
    //         $data['updated_by'] = $user->name;
    //         $data['updator_role'] = 'manager';
    //         $data['updator_id'] = $user->manager_id;
    //     }
    //     if($user->role == 'admin')
    //     {
    //         $data['updated_by'] = $user->name;
    //         $data['updator_role'] = 'admin';
    //         $data['updator_id'] = $user->admin_id;
    //     }
        
    //     Page::where('slug', $slug)->update($data); 

    //     return redirect()->back()->with('alert', 'Page Updated successfully ✅');
    // }
    
        public function update(Request $request, $id)
    {
        $user = Auth::user();
        $data = $request->validate([
            "title" => 'required',
            "for_movie_or_series" => 'required',
            'description' => 'required'
        ]);
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
        
        // Find the page by slug
        $page = Page::findOrFail($id);
    
        // If page found, update its attributes
        if ($page) {
            $page->update($data); 
    
            // Update the slug
            $page->slug = Str::slug(ucwords($request->get("title")), "-");
            $page->save();
    
            return redirect()->back()->with('alert', 'Page Updated successfully ✅');
        }
    
        // If page not found, return a redirect or error response
        return redirect()->back()->with('error', 'Page not found.');
    }

    public function managePage(Request $request, $pageId)
    {
        $post = Page::where('id', $pageId)->first();
        $status = $request->input('type');
        try{
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

    public function allDeletedPages(Request $request)
    {
        $query = $request->input('query');
                
        if(empty($query)){
            $page = Page::where('status', 2)->orderBy('updated_at', 'desc')->paginate(20);
        }else{
            $page = Page::where('status', 2)
                    ->where( function ($queryBuilder) use ($query) {
                        $queryBuilder->where('title', 'like', '%' . $query . '%')
                                     ->orWhere('description', 'like', '%' . $query . '%')
                                     ->orWhere('author', 'like', '%' . $query . '%');
                    })->orderBy('updated_at', 'desc')->paginate(20);
        }

        return view('admin.trash.deleted_page', compact('page'));
    }

    public function delete($id)
    {
        $deletePage = Page::where('id', $id)->delete();
        return redirect()->back()->with('alert', 'Page deleted ✅');
    }
}
