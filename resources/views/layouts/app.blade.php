<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Recipe Platform')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-lime-50">
<nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-lime-100">
    @include('layouts.navigation')
</nav>

@if($header ?? false)
    <header class="bg-gradient-to-r from-lime-500 to-lime-600 text-white py-10 shadow-lg shadow-lime-200/50">
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
