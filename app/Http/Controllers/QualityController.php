<?php

namespace App\Http\Controllers;

use App\Models\Quality;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class QualityController extends Controller
{
    public function allQualities() 
    {
        $qualities = Quality::withCount('posts')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.quality.allQuality', compact('qualities'));
    }
    public function createQuality() 
    {
        return view('admin.quality.createQuality');

    }

    public function createNewQuality(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'quality' => 'required|unique:qualities,quality'
        ]);

        Quality::create($data);

        return redirect()->back()->with('success', 'Categories inserted successfully!');
    }
    public function updateQuality(Request $request, $qualityId)
    {
        // Validate the form data
        try{
            $data = $request->validate([
                'quality' => 'required'
            ]);
            
            Quality::where('id', $qualityId)->update($data);
            
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'error' . $e);
        }


        return redirect()->back()->with('alert', 'Quality Updated successfully ✔!');
    }
    public function deleteQuality(Request $request, $qualityId)
    {
        try{
            $quality = Quality::findOrFail($qualityId);
            $quality->delete();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', "Warning ! Quality is in use . Can't delete quality which has post !");
        }

        return redirect()->back()->with('alert', 'Quality deleted successfully ✔!');
    }

}
