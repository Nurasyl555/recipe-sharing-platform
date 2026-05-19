<x-app-layout>
    <div class="space-y-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('Favorites') }}</h1>
            <p class="text-gray-600">{{ __('Your favorite recipes collected here.') }}</p>
        </div>

        @if($recipes->isEmpty())
            <div class="bg-white rounded-[2.5rem] p-12 text-center border border-dashed border-gray-300">
                <div class="bg-lime-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No favorite recipes yet') }}</h3>
                <p class="text-gray-500 mb-8">{{ __('Explore recipes and add them to your favorites.') }}</p>
                <a href="{{ route('recipes.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-2xl shadow-sm text-white bg-lime-600 hover:bg-lime-700">
                    {{ __('Discover Recipes') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recipes as $recipe)
                    <x-recipe-card :recipe="$recipe" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
