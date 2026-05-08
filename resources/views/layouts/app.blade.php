<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Recipe Platform')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
<nav class="bg-white shadow-md sticky top-0 z-50">
    @include('layouts.navigation')
</nav>

@if($header ?? false)
    <header class="bg-gradient-to-r from-orange-500 to-red-500 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
@endif

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{ $slot ?? '' }}
</main>

<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p>&copy; 2026 Recipe Sharing Platform. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
