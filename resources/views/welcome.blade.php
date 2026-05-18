<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Recipe Platform') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-lime-50 text-gray-900 font-sans antialiased selection:bg-lime-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-lime-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute top-1/2 -right-24 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse delay-700"></div>

        <!-- Header -->
        <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-8">
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-application-logo class="w-10 h-10 text-lime-600 fill-current" />
                    <span class="text-2xl font-black tracking-tight text-lime-900">{{ config('app.name', 'Recipes') }}</span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('recipes.index') }}" class="hidden md:block text-sm font-bold text-gray-600 hover:text-lime-600 transition-colors uppercase tracking-widest">
                        {{ __('messages.discover') }}
                    </a>

                    @if (Route::has('login'))
                        <div class="flex items-center gap-4 border-l border-gray-200 pl-6 ml-2">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-white bg-lime-600 hover:bg-lime-700 px-6 py-2.5 rounded-xl shadow-lg shadow-lime-200 transition active:scale-95">
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-700 hover:text-lime-600 transition-colors">
                                    {{ __('messages.log_in') }}
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-lime-600 hover:bg-lime-700 px-6 py-2.5 rounded-xl shadow-lg shadow-lime-200 transition active:scale-95">
                                        {{ __('messages.register') }}
                                    </a>
                                @endif
                            @endauth

                            {{-- Simple Language Switcher for Landing Page --}}
                            <div class="flex gap-2 ml-4">
                                <a href="{{ route('lang.switch', 'ru') }}" class="text-xs font-bold {{ app()->getLocale() == 'ru' ? 'text-lime-600' : 'text-gray-400' }}">RU</a>
                                <a href="{{ route('lang.switch', 'kk') }}" class="text-xs font-bold {{ app()->getLocale() == 'kk' ? 'text-lime-600' : 'text-gray-400' }}">KK</a>
                                <a href="{{ route('lang.switch', 'en') }}" class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-lime-600' : 'text-gray-400' }}">EN</a>
                            </div>
                        </div>
                    @endif
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex-grow flex items-center justify-center px-6">
            <div class="max-w-4xl text-center space-y-10">
                <div class="inline-block px-4 py-2 bg-lime-100 text-lime-700 rounded-full text-xs font-black uppercase tracking-[0.2em] animate-bounce">
                    🥗 {{ __('messages.discover_recipes') }}
                </div>

                <h1 class="text-6xl md:text-8xl font-black text-gray-900 leading-[1.1] tracking-tight">
                    {{ __('messages.welcome_title') }}
                </h1>

                <p class="text-xl md:text-2xl text-gray-600 max-w-2xl mx-auto leading-relaxed font-medium">
                    {{ __('messages.welcome_subtitle') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4">
                    <a href="{{ route('recipes.index') }}" class="w-full sm:w-auto text-lg font-black text-white bg-lime-600 hover:bg-lime-700 px-10 py-5 rounded-[2rem] shadow-2xl shadow-lime-300 transition-all hover:-translate-y-1 active:scale-95">
                        {{ __('messages.browse_recipes') }}
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="w-full sm:w-auto text-lg font-black text-lime-700 bg-white border-2 border-lime-200 hover:border-lime-400 px-10 py-5 rounded-[2rem] shadow-xl shadow-lime-100 transition-all hover:-translate-y-1 active:scale-95">
                            {{ __('messages.join_community') }}
                        </a>
                    @endguest
                </div>

                <!-- Stats/Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-20">
                    <div class="bg-white/50 backdrop-blur-md p-8 rounded-[2.5rem] border border-white shadow-sm">
                        <div class="text-3xl mb-3">👨‍🍳</div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ __('messages.easy_to_share') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('messages.easy_to_share_desc') }}</p>
                    </div>
                    <div class="bg-white/50 backdrop-blur-md p-8 rounded-[2.5rem] border border-white shadow-sm">
                        <div class="text-3xl mb-3">⭐</div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ __('messages.rate_and_review') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('messages.rate_and_review_desc') }}</p>
                    </div>
                    <div class="bg-white/50 backdrop-blur-md p-8 rounded-[2.5rem] border border-white shadow-sm">
                        <div class="text-3xl mb-3">❤️</div>
                        <h3 class="font-bold text-gray-900 mb-1">{{ __('messages.save_favorites') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('messages.save_favorites_desc') }}</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-10">
            <div class="pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-sm text-gray-500 font-medium">
                    {{ __('messages.footer_text') }}
                </p>
                <div class="flex items-center gap-6 text-sm font-bold text-gray-400">
                    <a href="#" class="hover:text-lime-600 transition-colors">{{ __('messages.privacy') }}</a>
                    <a href="#" class="hover:text-lime-600 transition-colors">{{ __('messages.terms') }}</a>
                    <a href="#" class="hover:text-lime-600 transition-colors">{{ __('messages.contact') }}</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
