<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Genres;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class GenresController extends Controller
{

    public function allGenres() 
    {
        $genres = Genre::withCount('posts')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.genres.allGenres', compact('genres'));
    }
    
    public function createGenres() 
    {
        return view('admin.genres.createGenres');

    }

    public function createNewGenres(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'genres_name' => 'required|unique:genres,genres_name'
        ]);

        Genre::create($data);

        return redirect()->back()->with('success', 'Genre inserted successfully!');
    }

    public function updateGenres(Request $request, $genresId)
    {
        // Validate the form data
        try{
            $data = $request->validate([
                'genres_name' => 'required'
            ]);
            
            Genre::where('id', $genresId)->update($data);
            
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'error' . $e);
        }


        return redirect()->back()->with('alert', 'Genre Updated successfully ✔!');
    }
    public function deleteGenres(Request $request, $genresId)
    {
        try{
            $genre = Genre::findOrFail($genresId);
            $genre->delete();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', "Warning ! Category is in use . Can't delete category which has post !");
        }

        return redirect()->back()->with('alert', 'Genre deleted successfully ✔!');
    }

}
