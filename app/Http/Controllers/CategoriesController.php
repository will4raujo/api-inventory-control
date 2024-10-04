<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Category created successfully'], 201);
    }

    public function findById(Request $request, int $id)
    {   
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid category id'], 400);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    // public function destroy($id)
    // {
    //     $id = intval($id)

    //     if (!is_numeric($id) || intval($id) <= 0) {
    //         return response()->json(['message' => 'Invalid category id'], 400);
    //     }

    //     $category = Category::find($id);
    //     if (!$category) {
    //         return response()->json(['message' => 'Category not found'], 404);
    //     }
    
    //     $products = Product::where('category_id', $id)->exists();
    //     if ($products) {
    //         return response()->json(['message' => 'Category has products and cannot be deleted'], 400);
    //     }
    
    //     $category->delete();
    
    //     return response()->json(['message' => 'Category deleted successfully'], 200);
    // }
}
