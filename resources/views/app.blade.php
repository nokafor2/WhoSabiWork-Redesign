<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- fontawesome css styles --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>WhoSabiWork</title>

        {{-- @vite('resources/js/app.js') --}}
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        {{-- This adds link to all the things Inertia requires --}}
        @inertiaHead
    </head>
    {{--  class="antialiased" --}}
    <body class="d-flex flex-column min-vh-100" style="background-color: #040D14">
        {{-- This inserts a div element that would hold the vue application --}}
        @inertia
        <div id="app">
            {{-- <artisan-index></artisan-index> --}}

        </div>
    </body>
</html>
