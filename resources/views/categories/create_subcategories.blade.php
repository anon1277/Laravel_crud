<!--subcategories.blade.php -->
@extends('layouts.layout')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <div>
        <h1>Add Subcategory</h1>
        <form action="{{ route('subcategories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="parent_category" class="form-label">Parent Category:</label>
                <select name="parent_category" class="form-control">
                    <option value="">Select Parent Category</option>
                    @foreach ($parentCategories as $parentCategory)
                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="subcategory_name" class="form-label">Subcategory Name:</label>
                <input type="text" name="subcategory_name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Subcategory</button>
        </form>
    </div>
</div>
@endsection
