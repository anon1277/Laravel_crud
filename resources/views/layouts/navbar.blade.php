<nav class="navbar navbar-expand-lg navbar-light bg-success">
    <a class="navbar-brand" href="#">Admin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">

        </li>
        <li class="nav-item">

        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Category
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('categories.create')}}">Add Category</a>
            <a class="dropdown-item" href="{{ route('sub.categories.create')}}">Add Sub Category</a>
            <a class="dropdown-item" href="{{ route('sub.categories.create')}}">Manage Category</a>

            <div class="dropdown-divider"></div>

            <ul class="category-list">
                @foreach ($categories as $category)
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="category-{{ $category->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $category->name }}
                        </a>
                        @if ($category->subcategories->count() > 0)
                            <ul class="dropdown-menu" aria-labelledby="category-{{ $category->id }}">
                                @foreach ($category->subcategories as $subcategory)
                                    <li><a class="dropdown-item" href="#">{{ $subcategory->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" href="#">Disabled</a>
        </li>
      </ul>

    </div>
  </nav>
