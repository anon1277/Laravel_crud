<!DOCTYPE html>
<html>
<title>W3.CSS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<body>

    <div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none" id="mySidebar">
        <button class="w3-bar-item w3-button w3-large" onclick="w3_close()">Close &times;</button>
        <a href="" class="w3-bar-item w3-button">View Categories</a>
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
        <a href="#" class="w3-bar-item w3-button">Manage Categories</a>
        <a href="#" class="w3-bar-item w3-button">Do More</a>
    </div>
    <div id="main">

        <div class="w3-teal">
            <button id="openNav" class="w3-button w3-teal w3-xlarge" onclick="w3_open()">&#9776;</button>
            <div class="w3-container">
                <h1>Admin</h1>
            </div>
        </div>

    </div>

    <script>
        function w3_open() {
            document.getElementById("main").style.marginLeft = "25%";
            document.getElementById("mySidebar").style.width = "25%";
            document.getElementById("mySidebar").style.display = "block";
            document.getElementById("openNav").style.display = 'none';
        }

        function w3_close() {
            document.getElementById("main").style.marginLeft = "0%";
            document.getElementById("mySidebar").style.display = "none";
            document.getElementById("openNav").style.display = "inline-block";
        }
    </script>

</body>

</html>
