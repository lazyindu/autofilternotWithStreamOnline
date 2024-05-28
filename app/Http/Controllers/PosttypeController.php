<?php

namespace App\Http\Controllers;

use App\Models\Posttype;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PosttypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allPostTypes()
    {
        $allTypes = Posttype::withCount('posts')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.posttype.allTypes', compact('allTypes'));
    }
    public function createTypes() 
    {
        return view('admin.posttype.createTypes');

    }

    public function createNewTypes(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'type_name' => 'required'
        ]);

        Posttype::create($data);

        return redirect()->back()->with('success', 'Categories inserted successfully!');
    }

    public function updateType(Request $request, $typeId)
    {
        // Validate the form data
        try{
            $data = $request->validate([
                'type_name' => 'required'
            ]);
            
            Posttype::where('id', $typeId)->update($data);
            
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'error' . $e);
        }


        return redirect()->back()->with('alert', 'Type Updated successfully ✔!');
    }
    public function deleteType(Request $request, $typeId)
    {
        try{
            $type = Posttype::findOrFail($typeId);
            $type->delete();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', "Warning ! Type is in use . Can't delete type which has post !");
        }

        return redirect()->back()->with('alert', 'Type deleted successfully ✔!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Posttype $posttype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posttype $posttype)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Posttype $posttype)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posttype $posttype)
    {
        //
    }
}
