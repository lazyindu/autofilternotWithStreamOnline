<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{

    public function reply(Request $request, $commentId)
    {
        $user = Auth::user();

        $data = $request->validate([
            'reply' => 'required'
        ]);

        try{
            $comment = Comment::findOrFail($commentId);

            $data['replied_by_user'] = $user->name;
            $data['replied_by_dp'] = $user->dp;

            $comment->replies()->create($data);

            $comment->update(['has_reply'=> true]);

            return redirect()->back()->with('success', 'Your comment has been added successfully!');

        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return redirect()->back()->with('error', 'The associated comment was not found.');

        }

    }
    public function updateReply(Request $request, $replyId)
    {
        $data = $request->validate([
            'reply' => 'required',
        ]);

        $reply = Reply::findOrFail($replyId);

        $reply->update(['reply'=>$data['reply']]);

        return redirect()->back()->with('success', 'Reply Updated successfully');
    }
    public function deleteReply(Request $request, $replyId)
    {
        Reply::where('id', $replyId)->delete();

        return redirect()->back()->with('success', 'Reply Deleted successfully');
    }

}
