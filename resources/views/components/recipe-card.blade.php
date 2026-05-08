<!-- resources/views/components/recipe-card.blade.php -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <img src="{{ $recipe->image_path }}" alt="{{ $recipe->title }}" class="w-full h-48 object-cover">
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900">{{ $recipe->title }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($recipe->description, 100) }}</p>

        <div class="flex justify-between items-center mt-4">
            <span class="text-yellow-500 text-sm">⭐ {{ $recipe->avg_rating }}</span>
            <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-800">View</a>
        </div>
    </div>
</div>
