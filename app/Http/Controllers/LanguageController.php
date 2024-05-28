<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function allLanguages() 
    {
        $languages = Language::withCount('posts')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.language.allLanguages', compact('languages'));
    }
    public function createLanguage() 
    {
        return view('admin.language.createLanguage');

    }

    public function createNewLanguage(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'language' => 'required'
        ]);

        Language::create($data);

        return redirect()->back()->with('success', 'Categories inserted successfully!');
    }
    public function updateLanguage(Request $request, $languageId)
    {
        // Validate the form data
        try{
            $data = $request->validate([
                'language' => 'required'
            ]);
            
            Language::where('id', $languageId)->update($data);
            
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'error' . $e);
        }


        return redirect()->back()->with('alert', 'Language Updated successfully ✔!');
    }
    public function deleteLanguage(Request $request, $languageId)
    {
        try{
            $language = Language::findOrFail($languageId);
            $language->delete();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', "Warning ! Language is in use . Can't delete language which has post !");
        }

        return redirect()->back()->with('alert', 'Language deleted successfully ✔!');
    }


}
