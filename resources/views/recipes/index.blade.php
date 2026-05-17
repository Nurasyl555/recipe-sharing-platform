<x-app-layout>
<!-- resources/views/recipes/index.blade.php -->
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('messages.discover_recipes') }}</h1>
        <p class="text-gray-600">{{ __('messages.explore') }}</p>
    </div>

    <!-- Filters -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-lime-100 mb-12">
        <form method="GET" action="{{ route('recipes.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-lime-800 ml-1">{{ __('messages.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.search_placeholder') }}"
                           class="w-full px-4 py-3 border border-lime-200 rounded-2xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition duration-150">
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-lime-800 ml-1">{{ __('messages.category') }}</label>
                    <select name="category" class="w-full px-4 py-3 border border-lime-200 rounded-2xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition duration-150">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cuisine -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-lime-800 ml-1">{{ __('messages.cuisine') }}</label>
                    <select name="cuisine" class="w-full px-4 py-3 border border-lime-200 rounded-2xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition duration-150">
                        <option value="">{{ __('messages.all_cuisines') }}</option>
                        @foreach($cuisines as $cuisine)
                            <option value="{{ $cuisine->id }}" @selected(request('cuisine') == $cuisine->id)>{{ $cuisine->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulty -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-lime-800 ml-1">{{ __('messages.difficulty') }}</label>
                    <select name="difficulty" class="w-full px-4 py-3 border border-lime-200 rounded-2xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition duration-150">
                        <option value="">{{ __('messages.any_difficulty') }}</option>
                        <option value="easy" @selected(request('difficulty') == 'easy')>{{ __('messages.easy') }}</option>
                        <option value="medium" @selected(request('difficulty') == 'medium')>{{ __('messages.medium') }}</option>
                        <option value="hard" @selected(request('difficulty') == 'hard')>{{ __('messages.hard') }}</option>
                    </select>
                </div>

                <!-- Max Time -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-lime-800 ml-1">{{ __('messages.max_time') }}</label>
                    <input type="number" name="max_time" value="{{ request('max_time') }}" placeholder="e.g. 60" min="1"
                           class="w-full px-4 py-3 border border-lime-200 rounded-2xl focus:ring-2 focus:ring-lime-500 focus:border-lime-500 transition duration-150">
                </div>

                <!-- Action Buttons -->
                <div class="md:col-span-2 lg:col-span-3 flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-lime-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-lime-700 shadow-lg shadow-lime-200/50 transition active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        {{ __('messages.apply_filters') }}
                    </button>
                    <a href="{{ route('recipes.index') }}" class="bg-gray-100 text-gray-600 px-6 py-3.5 rounded-2xl font-bold hover:bg-gray-200 transition active:scale-95">
                        {{ __('messages.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Recipes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($recipes as $recipe)
            <x-recipe-card :recipe="$recipe" />
        @empty
            <div class="col-span-full text-center py-12">
            <p class="text-gray-500 text-lg">{{ __('messages.no_recipes') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $recipes->links() }}
    </div>
</div>
</x-app-layout>
