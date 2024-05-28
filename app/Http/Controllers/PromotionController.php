<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PromotionController extends Controller
{

    public function addAdmin()
    {
       if (Auth::user()->super_admin) {
           return view('admin.promote.addNewAdmin');
    } else {
            return redirect()->back()->with('alert', 'You are not allowed to do this swwetheart 🥱');
       }
       
    }
    public function addNewAdmin(Request $request)
    {
        $user = Auth::user();

        if($user->super_admin)
        {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'address' => 'nullable',
                'remarks' => 'nullable',
                'dp' => 'required|image|mimes:jpeg,png,gif,svg',
                'lucky_no' => 'required',
                'can_crud_manager' => 'nullable',
                'super_admin' => 'nullable', 
                'role' => 'required', 
            ]);
    
            if($request->hasFile('dp')){
                $imageName = time() . '.' . $request->dp->getClientOriginalExtension();
                $request->dp->move(public_path('/profile_pic'), $imageName);
                $data['dp'] = $imageName;
            }
    
            $data['admin_id'] = 'A_' . Str::random(10) . '_IN' ; 
            Admin::create($data);
            return redirect()->back()->with('alert', 'A New Admin inserted');

        }else{

            $restrict = Admin::findOrFail($user->id);
            $restrict->update(['status' => 2]);

            return redirect()->back()->with('alert', 'You Can access route but ! You dont have right to Add new admins ! From now, Your account is restricted 😂');
        }
    }

    public function updateAdmin(Request $request, $adminId)
    {
        $user = Auth::user();
        if($user->can_crud_manager){
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'nullable',
                'dp' => 'nullable|image|mimes:jpeg,png,gif,svg',
                'lucky_no' => 'required',
                'can_crud_manager' => 'nullable',
                'super_admin' => 'nullable',
    
            ]);
    
            $admin = Admin::findOrFail($adminId);
    
            // updating new image
            if($request->hasFile('dp')){
                
                // unlinking current image
                $currentImage = $admin->dp;
                $imagePath = public_path('/profile_pic'.'/'.$currentImage);
                if(file_exists($imagePath)){
                    unlink($imagePath);
                }
    
                // uploading current image
                $imageName = time() . '.' . $request->dp->getClientOriginalExtension();
                $request->dp->move(public_path('/profile_pic'), $imageName);
                $data['dp'] = $imageName;
            }
    
            $admin->update($data);
    
            return redirect()->back()->with('alert', 'Credentials updated ✔');
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }
    }

    public function rolebackStatus($role, $id)
    {   

        $user = Auth::user();
        if($user->super_admin){
            $lazy_user = Admin::findOrFail($id);
            
            $newStatus = $lazy_user->status == 1 ? 2 : 1 ;

            $lazy_user->update(['status' => $newStatus]);

            return redirect()->back()->with('alert', 'User Status Rolled Back ✔');
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }
    }

    public function allManager()
    {
        $user = Auth::user();
        if($user->super_admin){
            // $managers = Manager::whereIn('status', [1, 2])->orderBy('created_at', 'desc')->paginate(10);
            $admins = Admin::whereIn('status', [1, 2])->orderBy('created_at', 'desc')->paginate(8);
            return view('admin.promote.allManager', compact('admins'));
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }

    }
    public function deleteUser($role, $id)
    {
        $user = Auth::user();

        if($user->super_admin){
            $lazy_user = Admin::findOrFail($id);
            $lazy_user->update(['status' => 3]);
            return redirect()->back()->with('alert', 'User Deleted Successfully ✔');
        }else{
            return redirect()->back()->with('alert', 'Sorry dude ! You Dont have rights to do this operation ❌');
        }


    }

// useless section
    public function addNewManager(Request $request)
    {
        $user = Auth::user();
        if($user->type == 'pro'){
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'type' => 'required',
                'address' => 'nullable',
                'remarks' => 'nullable',
                'dp' => 'nullable|image|mimes:jpeg,png,gif,svg',
            ]);
    
            if($request->hasFile('dp')){
                $imageName = time() . '.' . $request->dp->getClientOriginalExtension();
                $request->dp->move(public_path('/profile_pic'), $imageName);
                $data['dp'] = $imageName;
            }
    
            $data['manager_id'] = 'M_' . Str::random(10) . '_IN' ; 
            Manager::create($data);
        }else{
            $restrict = Manager::findOrFail($user->id);
            $restrict->update(['status' => 2]);

            return redirect()->back()->with('alert', 'You Can access route but ! You dont have right to Add new Manager ! From now, Your account is restricted 😂');
        }
        return redirect()->back()->with('alert', 'A New Manager inserted');
    }

    public function promoteUser(Request $request, $managerId)
    {   
        $data = $request->validate([
            'type' => 'required'
        ]);
        Manager::findOrFail($managerId)->update(['type' => $data['type']]);

        return redirect()->back()->with('alert', 'User Promoted to '.$data['type'].' ✔');
    }
    

    public function updateManager(Request $request, $managerId)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'nullable',
            'dp' => 'nullable|image|mimes:jpeg,png,gif,svg',

        ]);

        // unlinking previous image
        $manager = Manager::findOrFail($managerId);

        // updating new image
        if($request->hasFile('dp')){
            
            // unlinking current image
            $currentImage = $manager->dp;
            $imagePath = public_path('/profile_pic'.'/'.$currentImage);
            if(file_exists($imagePath)){
                unlink($imagePath);
            } 

            // uploading current image
            $imageName = time() . '.' . $request->dp->getClientOriginalExtension();
            $request->dp->move(public_path('/profile_pic'), $imageName);
            $data['dp'] = $imageName;
        }

        $manager->update($data);

        return redirect()->back()->with('alert', 'Credentials updated ✔');
    }



    public function addManager()
    {
        return view('admin.promote.addNewManager');
    }

}
