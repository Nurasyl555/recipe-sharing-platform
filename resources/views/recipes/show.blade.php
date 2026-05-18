<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Hero Section -->
        <div class="relative rounded-[3rem] overflow-hidden shadow-2xl mb-12 h-[500px] group">
            @if($recipe->image)
                <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}"
                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-1000">
            @else
                <div class="w-full h-full bg-gradient-to-br from-lime-100 to-lime-200 flex items-center justify-center">
                    <svg class="w-32 h-32 text-lime-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

            <div class="absolute bottom-10 left-10 right-10">
                <div class="flex flex-wrap gap-3 mb-4">
                    <span class="backdrop-blur-md bg-white/20 text-white text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full ring-1 ring-white/30">
                        {{ $recipe->cuisine->name ?? 'World' }}
                    </span>
                    <span class="backdrop-blur-md bg-lime-500/50 text-white text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full ring-1 ring-white/30">
                        {{ $recipe->category->name ?? 'Dish' }}
                    </span>
                </div>
                <h1 class="text-5xl md:text-6xl font-black text-white leading-tight mb-4 drop-shadow-lg">
                    {{ $recipe->title }}
                </h1>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        @if($recipe->user->avatar)
                            <img src="{{ asset('storage/' . $recipe->user->avatar) }}" class="w-10 h-10 rounded-full border-2 border-white">
                        @else
                            <div class="w-10 h-10 rounded-full bg-lime-500 flex items-center justify-center text-white font-bold border-2 border-white">
                                {{ substr($recipe->user->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="text-white font-medium">{{ __('messages.by') }} {{ $recipe->user->name }}</span>
                    </div>
                    <div class="h-4 w-px bg-white/30"></div>
                    <div class="flex items-center text-yellow-400 gap-1 font-bold text-lg">
                        ⭐ {{ round($recipe->ratings->avg('score') ?? 0, 1) }}
                        <span class="text-white/60 text-sm font-normal">({{ $recipe->ratings->count() }} {{ __('messages.reviews') }})</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left Column: Details -->
            <div class="lg:col-span-8 space-y-12">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-gray-50 p-6 rounded-[2rem] text-center border border-gray-100 shadow-sm hover:bg-white hover:shadow-xl transition-all duration-300">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">{{ __('messages.prep') }}</p>
                        <p class="text-xl font-black text-gray-900">{{ $recipe->prep_time }} <span class="text-xs font-medium">{{ __('messages.min') }}</span></p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-[2rem] text-center border border-gray-100 shadow-sm hover:bg-white hover:shadow-xl transition-all duration-300">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">{{ __('messages.cook') }}</p>
                        <p class="text-xl font-black text-gray-900">{{ $recipe->cook_time }} <span class="text-xs font-medium">{{ __('messages.min') }}</span></p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-[2rem] text-center border border-gray-100 shadow-sm hover:bg-white hover:shadow-xl transition-all duration-300">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">{{ __('messages.serves') }}</p>
                        <p class="text-xl font-black text-gray-900">{{ $recipe->servings }} <span class="text-xs font-medium">{{ __('messages.ppl') }}</span></p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-[2rem] text-center border border-gray-100 shadow-sm hover:bg-white hover:shadow-xl transition-all duration-300">
                        <p class="text-gray-400 text-xs font-bold uppercase mb-1">{{ __('messages.level') }}</p>
                        <p class="text-xl font-black text-gray-900">
                            @if($recipe->difficulty == 'easy') {{ __('messages.easy') }}
                            @elseif($recipe->difficulty == 'medium') {{ __('messages.medium') }}
                            @elseif($recipe->difficulty == 'hard') {{ __('messages.hard') }}
                            @else {{ ucfirst($recipe->difficulty) }}
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed italic">
                    "{{ $recipe->description }}"
                </div>

                <!-- Ingredients -->
                <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-lime-50">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 bg-lime-100 text-lime-600 rounded-xl flex items-center justify-center">🥗</span>
                        {{ __('messages.ingredients') }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($recipe->ingredients as $ingredient)
                            <label class="flex items-center p-4 rounded-2xl hover:bg-lime-50/50 cursor-pointer transition-colors group">
                                <input type="checkbox" class="w-6 h-6 rounded-lg border-lime-200 text-lime-500 focus:ring-lime-500 mr-4">
                                <span class="text-gray-700 font-medium group-hover:text-gray-900 transition-colors">
                                    {{ $ingredient->name }}
                                    <span class="text-gray-400 ml-1 font-normal">— {{ $ingredient->pivot->amount ?? '' }} {{ $ingredient->pivot->unit ?? '' }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Instructions -->
                <div class="space-y-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                        <span class="w-10 h-10 bg-lime-100 text-lime-600 rounded-xl flex items-center justify-center">👨‍🍳</span>
                        {{ __('messages.cooking_steps') }}
                    </h2>
                    <div class="space-y-10">
                        @php
                            $steps = explode("\n", $recipe->instructions);
                            $steps = array_filter($steps, fn($value) => !empty(trim($value)));
                        @endphp
                        @foreach($steps as $index => $step)
                            <div class="flex gap-8 relative group">
                                <div class="flex-none w-16 h-16 rounded-3xl bg-lime-50 text-lime-300 group-hover:bg-lime-500 group-hover:text-white transition-all duration-500 flex items-center justify-center text-2xl font-black">
                                    {{ sprintf('%02d', $index + 1) }}
                                </div>
                                <div class="pt-2">
                                    <p class="text-xl text-gray-700 leading-relaxed font-medium group-hover:text-gray-900 transition-colors">
                                        {{ trim($step) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions & Meta -->
            <div class="lg:col-span-4 space-y-8">
                @auth
                    <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-xl">
                        <h3 class="text-xl font-bold mb-6">{{ __('messages.recipe_actions') }}</h3>
                        <div class="space-y-4">
                            @if(auth()->id() === $recipe->user_id || auth()->user()->isAdmin())
                                <a href="{{ route('recipes.edit', $recipe) }}"
                                   class="w-full flex items-center justify-center gap-2 bg-white text-gray-900 px-6 py-4 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    {{ __('messages.edit_details') }}
                                </a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full bg-white/10 text-white/70 px-6 py-4 rounded-2xl font-bold hover:bg-red-500 hover:text-white transition-all">
                                        {{ __('messages.delete_recipe') }}
                                    </button>
                                </form>
                            @endif

                            <div x-data="{ isFavorite: {{ auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists() ? 'true' : 'false' }} }">
                                <form action="{{ route('favorites.store', $recipe->id) }}" method="POST" x-show="!isFavorite">
                                    @csrf
                                    <button type="submit" class="w-full bg-orange-500 text-white px-6 py-4 rounded-2xl font-bold hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        {{ __('messages.save_to_collection') }}
                                    </button>
                                </form>
                                <form action="{{ route('favorites.destroy', $recipe->id) }}" method="POST" x-show="isFavorite">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-white/10 text-white/90 px-6 py-4 rounded-2xl font-bold hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                                        ❤️ {{ __('messages.in_your_collection') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Review Section in Sidebar -->
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('messages.reviews') }}</h3>

                    @auth
                        <form action="{{ route('ratings.store') }}" method="POST" class="mb-10">
                            @csrf
                            <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">

                            <div class="mb-6">
                                <div class="flex gap-2 flex-row-reverse justify-end" style="width: max-content;">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="score" value="{{ $i }}" class="peer hidden" required />
                                        <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-200 peer-hover:text-yellow-400 peer-checked:text-yellow-400 transition-colors">⭐</label>
                                    @endfor
                                </div>
                            </div>

                            <div class="mb-4">
                                <textarea name="comment" placeholder="{{ __('messages.tell_us_what_you_think') }}" required
                                          class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 min-h-[120px]"></textarea>
                            </div>

                            <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-2xl font-bold hover:bg-lime-500 transition-all">
                                {{ __('messages.post_review') }}
                            </button>
                        </form>
                    @endauth

                    <div class="space-y-6">
                        @forelse($recipe->ratings->sortByDesc('created_at')->take(5) as $rating)
                            <div class="pb-6 border-b border-gray-50 last:border-0 last:pb-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ substr($rating->user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-800 text-sm">{{ $rating->user->name }}</span>
                                    <span class="text-yellow-400 text-xs ml-auto">{{ str_repeat('⭐', $rating->score) }}</span>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ $rating->comment }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center italic">{{ __('messages.no_reviews_yet') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
