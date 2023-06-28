<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Category $category)
    {
        // Retrieve all subcategories for the given category
        $subcategories = $category->subcategories;

        return view('admin.subcategories.index', compact('category', 'subcategories'));
    }


      //method to display  Create Sub Category Form
      public function show_sub_category_form()
      {
        $parentCategories = Category::all(); // Fetch all parent categories from the database

        return view('categories.create_subcategories', compact('parentCategories'));
    }


public function store(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        'parent_category' => 'required|exists:categories,id',
        'subcategory_name' => 'required|max:255',
    ]);


    // Create a new subcategory instance and associate it with the parent category
    $subcategory = new Subcategory;
    $subcategory->name = $validatedData['subcategory_name'];

    // Find the parent category
    $parentCategory = Category::findOrFail($validatedData['parent_category']);

    // Save the subcategory to the parent category's subcategories relationship
    $parentCategory->subcategories()->save($subcategory);

    return redirect()->route('employees.index')->with('success', 'Category added successfully.');
}
}
