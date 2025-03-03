<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}
    <title>WhoSabiWork - @yield('title')</title>
</head>
<body style="background-color: #040D14">
    <nav class="navbar navbar-expand-xl navbar-light bg-white fixed-top shadow-sm">
        <div class="container-fluid">
            {{-- Initial position of logo --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bolder" href="{{ route('home.photoFeeds') }}">
                {{-- <img src="#" alt="Website Logo" class="img-fluid" height="24"> --}}
                WhoSabiWork
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-pills me-auto mb-2 mb-xl-0">
                    <li class="nav-item">
                        {{-- <a class="nav-link active" aria-current="page" href="#">Home</a> --}}
                        <a class="nav-link text-danger" href="{{ route('home.photoFeeds') }}">Photo Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('home.mobileMarket') }}">Mobile Market</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('artisans.index') }}">Artisans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('mechanics.index') }}">Mechanics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('home.spareParts') }}">Spare Parts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('home.contactUs') }}">Contact Us</a>
                    </li>
                </ul>

                <ul class="navbar-nav nav-pills ms-auto me-2 mb-2 mb-xl-0 gap-2">
                    @guest
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class=" btn btn-danger btn-sm text-light">Sign Up</a>
                            </li>                            
                        @endif                        
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-danger btn-sm text-light">Sign In</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="btn btn-danger btn-sm text-light"
                            {{-- This javascript controls the logout form --}}
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            >Sign Out ({{ Auth::user()->first_name }}, {{ Auth::user()->last_name }})</a>
                        </li>

                        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>

                <form class="d-flex mb-2 mb-xl-0" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-sm btn-outline-success" type="submit">Search</button>
                </form>             
            </div>
        </div>
    </nav>
    <div class="container bg-white my-5" style="position: relative; top: 15px; min-height: 600px">
        {{-- Display the flash message --}}
        <div>
            @if (session('status'))
                <div class="alert alert-success mt-3 mb-3">
                    {{ session('status') }}
                </div>
            @endif
        </div>
        
        <div class="row">
            @yield('content')
        </div>
    </div>
    {{-- <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
            }, false)
        })
        })()    
    </script>       --}}
</body>
</html>