<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @if($recipe->image)
                <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}"
                     class="w-full h-96 object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full h-96 bg-orange-100 flex items-center justify-center rounded-lg shadow-lg">
                    <span class="text-orange-400 text-xl">No image available</span>
                </div>
            @endif

            <div class="mt-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">{{ $recipe->title }}</h1>
                        <p class="text-gray-600 mt-2">By {{ $recipe->user->name }}</p>
                    </div>

                    <div class="text-right">
                        <div class="text-3xl font-bold text-yellow-500">⭐ {{ round($recipe->ratings->avg('score') ?? 0, 1) }}</div>
                        <p class="text-gray-600">({{ $recipe->ratings->count() }} reviews)</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-6 bg-gray-100 p-4 rounded-lg">
                    <div>
                        <p class="text-gray-600 text-sm">Prep Time</p>
                        <p class="text-lg font-bold">{{ $recipe->prep_time }} min</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Cook Time</p>
                        <p class="text-lg font-bold">{{ $recipe->cook_time }} min</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Servings</p>
                        <p class="text-lg font-bold">{{ $recipe->servings }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Ingredients</h2>
                    <ul class="space-y-2">
                        @foreach($recipe->ingredients as $ingredient)
                            <li class="flex items-center text-gray-700">
                                <span class="w-2 h-2 bg-orange-500 rounded-full mr-3"></span>
                                {{ $ingredient->name }} - {{ $ingredient->pivot->amount ?? '' }} {{ $ingredient->pivot->unit ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Instructions</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($recipe->instructions)) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @auth
                <div class="space-y-2">
                    @if(auth()->id() === $recipe->user_id)
                        <a href="{{ route('recipes.edit', $recipe) }}"
                           class="w-full block bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-center transition">
                            Edit Recipe
                        </a>
                        <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                Delete Recipe
                            </button>
                        </form>
                    @endif

                    <div x-data="{ isFavorite: {{ auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists() ? 'true' : 'false' }} }">
                        <form action="{{ route('favorites.store', $recipe->id) }}" method="POST" x-show="!isFavorite">
                            @csrf
                            <button type="submit" class="w-full bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">
                                🤍 Add to Favorites
                            </button>
                        </form>
                        <form action="{{ route('favorites.destroy', $recipe->id) }}" method="POST" x-show="isFavorite">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                                ❤️ Remove from Favorites
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="w-full block bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 text-center transition">
                    Login to Rate & Save
                </a>
            @endauth

            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="font-bold text-gray-900 mb-3">Recipe Info</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-600">Category</p>
                        <span class="text-orange-500 font-medium">{{ $recipe->category->name ?? 'None' }}</span>
                    </div>
                    <div>
                        <p class="text-gray-600">Cuisine</p>
                        <span class="text-orange-500 font-medium">{{ $recipe->cuisine->name ?? 'None' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Reviews</h2>

        @auth
            <form action="{{ route('ratings.store') }}" method="POST" class="mb-8 border-b pb-8">
                @csrf
                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Your Rating</label>
                    <div class="flex gap-2 flex-row-reverse justify-end" style="width: max-content;">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="score" value="{{ $i }}" class="peer hidden" required />
                            <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 peer-hover:text-yellow-400 peer-checked:text-yellow-500">
                                ⭐
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="mb-4">
                    <textarea name="comment" placeholder="Share your thoughts..." required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"></textarea>
                </div>

                <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">
                    Post Review
                </button>
            </form>
        @endauth

        <div class="space-y-4">
            @forelse($recipe->ratings as $rating)
                <div class="border-t pt-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-900">{{ $rating->user->name }}</p>
                            <p class="text-yellow-500">{{ str_repeat('⭐', $rating->score) }}</p>
                        </div>
                        @auth
                            @if(auth()->id() === $rating->user_id)
                                <form action="{{ route('ratings.destroy', $recipe->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="text-gray-700 mt-2">{{ $rating->comment }}</p>
                    <p class="text-gray-500 text-sm mt-2">{{ $rating->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review!</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
