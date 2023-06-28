<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Retrieve all categories from the database
        $categories = Category::all();

        return view('categories.index', compact('categories'));

    }

    public function show_category_with_subcategories()
    {
        $categories = Category::with('subcategories')->get(); // Eager load the subcategories relationship for all categories

        return view('categories.show', compact('categories'));
    }

    //method to display  Create Category    Form
    public function show_category_form()

        {
            // dd('Creating');
            return view('categories.create_categories');
        }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'category_name' => 'required|max:255',
        ]);

        // Create a new category instance
        $category = new Category;
        $category->name = $validatedData['category_name'];
        $category->save();

        return redirect()->route('employees.index')->with('success', 'Category added successfully.');
    }
}
