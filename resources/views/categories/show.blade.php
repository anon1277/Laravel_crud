@foreach ($categories as $category)
    <h3>{{ $category->name }}</h3>
    <ul>
        @foreach ($category->subcategories as $subcategory)
            <li>{{ $subcategory->name }}</li>
        @endforeach
    </ul>
    <div class="dropdown-divider" href="{{ route('categories.show', ['category' => $category->id]) }}">View Category</div>
@endforeach
