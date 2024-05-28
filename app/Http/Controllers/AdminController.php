<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ContactUs;
use App\Models\DMCA;
use App\Models\Post;
use App\Models\RequestUs;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function login(Request $request)
    {
        if($request->method() == "POST"){
            $data = $request->only(["email", "password", "lucky_no"]);
            if(Auth::guard('admin')->attempt($data)){                
                return redirect()->route('admin.dashboard');
            }else {
                return redirect()->back()->with("alert","Please enter valid email or password");
            }
        }
        return view('admin.login');
    }
    // public function managerLogin(Request $request)
    // {
    //     if($request->method() == "POST"){
    //         $data = $request->only(['email', 'password']);

    //         if(Auth::guard("manager")->attempt($data)){
    //            return redirect()->route('manager.dashboard');
    //         }else{
    //             return redirect()->back()->with("alert","Please enter valid email or password");
    //         }
    //     }

    //     return view('admin.managers.login');
    // }

    public function index(Request $request)
    {
        $query = $request->input('query');
                
        if(empty($query)){
            $posts = Post::orderBy('updated_at', 'desc')->paginate(15);
        }else{
            $posts = Post::where('title', 'like', '%' . $query . '%')
                            ->orWhere('movie_name', 'like', '%' . $query . '%')
                            ->orWhere('release_year', 'like', '%' . $query . '%')
                            ->orWhere('format', 'like', '%' . $query . '%')
                            ->orWhere('pixels', 'like', '%' . $query . '%')
                            ->orWhere('min_size', 'like', '%' . $query . '%')
                            ->orWhere('med_size', 'like', '%' . $query . '%')
                            ->orWhere('max_size', 'like', '%' . $query . '%')
                            ->orWhere('author', 'like', '%' . $query . '%')
                            ->orderBy('created_at', 'desc') // Add an order by clause if needed
                            ->paginate(20);
        }
        return view('admin.dashboard', compact('posts'));
    }

    public function accountDetails()
    {   $user = Auth::user();
        $item = Admin::findOrFail($user->id);
        return view('admin.account.details', compact('item'));
    }

    public function accountSecurity()
    {
        return view('admin.account.security');
    }
// call RCD
    public function contactUsMessages()
    {
        $data =  ContactUs::where('status', 1)->paginate(10);
        return view('admin.extras.contactsMessages', compact('data'));
    }
    public function dmcaReports()
    {
        $data =  DMCA::where('status', 1)->paginate(10);
        return view('admin.extras.dmcas', compact('data'));
    }
    public function requestUsMessages()
    {
        $data =  RequestUs::where('status', 1)->paginate(10);
        return view('admin.extras.requests', compact('data'));
    }
   
//    CALL READ RCD 
    public function contactUsMessagesRead()
    {
        $data =  ContactUs::where('status', 2)->paginate(10);
        return view('admin.extras.contactsMessages_Read', compact('data'));
    }
    public function dmcaReportsRead()
    {
        $data =  DMCA::where('status', 2)->paginate(10);
        return view('admin.extras.dmcas_Read', compact('data'));
    }
    public function requestUsMessagesRead()
    {
        $data =  RequestUs::where('status', 2)->paginate(10);
        return view('admin.extras.requests_Read', compact('data'));
    }
    // CHANGE STATUS OF RCD
    public function contactUsMessagesRollback($id)
    {
        $data =  ContactUs::where('id', $id)->update(['status'=> 2]);
        return redirect()->back()->with('alert', '✅ Status Rolled back - Marked as Done');
    }
    public function dmcaReportsRollback($id)
    {
        $data =  DMCA::where('id', $id)->update(['status'=> 2]);
        return redirect()->back()->with('alert', '✅ Status Rolled back - Marked as Done');
    }
    public function requestUsMessagesRollback($id)
    {
        $data =  RequestUs::where('id', $id)->update(['status'=> 2]);
        return redirect()->back()->with('alert', '✅ Status Rolled back - Marked as Done');
    }
    public function trackerbylazydev(Request $request)
    {
        $data = Visitor::orderBy('created_at','desc')->limit(500)->get();
        return view('admin.trackVisitor.trackerbylazydev', compact('data'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('alert','Logged out ✔');
    }

}
