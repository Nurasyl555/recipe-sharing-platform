<x-app-layout>
<!-- resources/views/recipes/index.blade.php -->
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Discover Recipes</h1>
        <p class="text-gray-600">Explore thousands of delicious recipes from around the world</p>
    </div>

    <!-- Фильтры -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Search recipes..."
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">

            <select name="cuisine" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">All Cuisines</option>
                @foreach($cuisines as $cuisine)
                    <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Рецепты -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($recipes as $recipe)
            <x-recipe-card :recipe="$recipe" />
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No recipes found</p>
            </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    <div class="mt-8">
        {{ $recipes->links() }}
    </div>
</div>
</x-app-layout>
