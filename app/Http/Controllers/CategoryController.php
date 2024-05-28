<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCategories() 
    {
        $categories = Category::withCount('posts')->orderBy('category_name', 'asc')->paginate(10);

        return view('admin.category.allCategory', compact('categories'));
    }
    public function createCategory() 
    {
        return view('admin.category.createCategory');

    }
    public function createNewCategory(Request $request)
    {
        // Validate the form data
        $data = $request->validate([
            'category_name' => 'required|unique:categories,category_name'
        ]);

        Category::create($data);

        return redirect()->back()->with('alert', 'Category inserted successfully!');
    }
    public function updateCategory(Request $request, $categoryId)
    {
        // Validate the form data
        try{
            $data = $request->validate([
                'category_name' => 'required'
            ]);
            
            Category::where('id', $categoryId)->update($data);
            
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', 'error' . $e);
        }


        return redirect()->back()->with('alert', 'Category Updated successfully ✔!');
    }
    public function deleteCategory(Request $request, $categoryId)
    {
        try{
            $category = Category::findOrFail($categoryId);
            $category->delete();
        }catch(ModelNotFoundException $e){
            return redirect()->back()->with('alert', "Warning ! Category is in use . Can't delete category which has post !");
        }

        return redirect()->back()->with('alert', 'Category deleted successfully ✔!');
    }

}
