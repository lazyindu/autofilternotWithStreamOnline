<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //  note
    //  status - 1 => Active Comment
    //  status - 2 => deleted Comment
    //  status -  =>  Comment

    public function comments()
    {
        //  status - 1 => Active Comment
        //  status - 2 => deleted Comment
        $newComments = Post::with(['comments' => function($query){
            $query->where(['status'=> 1, 'has_reply' => false]);
        }])->orderBy('created_at', 'desc')->get();

        return view("admin.comments.allComments", compact('newComments'));
    }

    public function repliedComments()
    {
        //  status - 1 => Active Comment
        //  status - 2 => deleted Comment
        $repliedComments = Post::with(['comments' => function($query){
            $query->where(['status'=> 1, 'has_reply' => true]);
        }])->orderBy('created_at', 'desc')->get();

        return view("admin.comments.repliedComments", compact('repliedComments'));
    }

    public function ignoredComments()
    {
        $ignoredComments = Post::with(['comments' => function($query){
            $query->where(['status'=> 2, 'has_ignored' => true]);
        }])->orderBy('created_at', 'desc')->get();

        return view("admin.comments.ignoredComments", compact('ignoredComments'));
    }

    // Actions
    public function ignoreComment($commentId)
    { 

        $data = Comment::where('id', $commentId)->update(['status'=> 2, 'has_ignored' => true]);

        return redirect()->back()->with('success','Comment added to ignored list');
    }

    public function undoIgnore($commentId)
    { 

        $data = Comment::where('id', $commentId)->update(['status'=> 1, 'has_ignored' => false]);

        return redirect()->back()->with('success','Comment undo ignored.');
    }

    public function deleteComment($commentId)
    { 
        //  status - 1 => Active Comment
        //  status - 2 => deleted Comment
        $data = Comment::where('id', $commentId)->update(['status' => 3]);
        
        return redirect()->back()->with('success','Comment added to trash bin');
    }

    public function undoDelete($commentId)
    { 
        //  status - 1 => Active Comment
        //  status - 2 => ignored Comment
        //  status - 2 => deleted Comment
        $data = Comment::where('id', $commentId)->first();
        if($data->has_reply && $data->has_ignored)
        {
            $data->update(['status' => 2]);
        }else{
            $data->update(['status' => 1]);
            
        }

        return redirect()->back()->with('success','Comment added to trash bin');
    }

    public function deletePermanent($commentId)
    {
        //  status - 1 => Active Comment
        //  status - 2 => deleted Comment

        $user = Auth::user();
        if($user->super_admin){
            $deletedComments = Post::with(['comments' => function($query){
                $query->where('status', 3);
            }])->orderBy('updated_at', 'desc')->paginate(30);
            return redirect()->back()->with('alert','Comment deleted permanently');
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }

    }

    


    // recycle bin

    public function recycleComment()
    {

        $user = Auth::user();
        if($user->role == 'admin'){
            $deletedComments = Post::with(['comments' => function($query){
                $query->where('status', 3);
            }])->orderBy('updated_at', 'desc')->paginate(30);
            return view("admin.trash.deleted_comment", compact('deletedComments'));
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }

    }
}
