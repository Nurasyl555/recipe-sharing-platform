<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-lime-50">
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center">
                    <x-application-logo class="w-20 h-20 fill-current text-lime-600" />
                    <h1 class="mt-2 text-2xl font-bold text-lime-800 tracking-tight">{{ config('app.name', 'Recipe Platform') }}</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl shadow-lime-200/50 overflow-hidden sm:rounded-2xl border border-lime-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
