@props(['recipe'])

<div class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 flex flex-col h-full relative">
    <!-- Image Section -->
    <div class="relative h-64 w-full overflow-hidden">
        @if($recipe->image)
            <img src="{{ asset('storage/' . $recipe->image) }}"
                 alt="{{ $recipe->title }}"
                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        @else
            <div class="w-full h-full bg-gradient-to-br from-lime-50 to-lime-100 flex items-center justify-center">
                <svg class="w-16 h-16 text-lime-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif

        <!-- Glassmorphism Badges -->
        <div class="absolute top-5 left-5 flex flex-col gap-2">
            <span class="backdrop-blur-md bg-white/70 text-lime-700 text-[10px] uppercase tracking-widest font-bold px-4 py-1.5 rounded-full shadow-sm">
                {{ $recipe->cuisine->name ?? 'World' }}
            </span>
        </div>

        <div class="absolute top-5 right-5"
             x-data="{
                isFavorite: {{ auth()->check() && auth()->user()->favorites()->where('recipe_id', $recipe->id)->exists() ? 'true' : 'false' }},
                loading: false,
                async toggleFavorite() {
                    @guest
                        window.location.href = '{{ route('login') }}';
                        return;
                    @endguest
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        if (this.isFavorite) {
                            await axios.delete('{{ route('favorites.destroy', $recipe->id) }}');
                            this.isFavorite = false;
                        } else {
                            await axios.post('{{ route('favorites.store', $recipe->id) }}');
                            this.isFavorite = true;
                        }
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                }
             }">
            <button @click="toggleFavorite()"
                    :disabled="loading"
                    class="p-2.5 bg-white/80 backdrop-blur-md rounded-full transition-all duration-300 shadow-sm"
                    :class="isFavorite ? 'text-red-500 bg-white' : 'text-gray-400 hover:text-red-500 hover:bg-white'">
                <svg class="w-5 h-5" :fill="isFavorite ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Time Overlay -->
        <div class="absolute bottom-4 left-5">
            <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $recipe->prep_time + $recipe->cook_time }} {{ __('messages.min') }}
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="p-7 flex-1 flex flex-col">
        <div class="mb-3">
            <p class="text-lime-600 text-xs font-bold uppercase tracking-wider mb-1">
                {{ $recipe->category->name ?? 'Uncategorized' }}
            </p>
            <h3 class="text-2xl font-bold text-gray-900 leading-tight group-hover:text-lime-600 transition-colors duration-300">
                <a href="{{ route('recipes.show', $recipe) }}">
                    {{ $recipe->title }}
                </a>
            </h3>
        </div>

        <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed">
            {{ $recipe->description }}
        </p>

        <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    @if($recipe->user->avatar)
                        <img src="{{ asset('storage/' . $recipe->user->avatar) }}" alt="{{ $recipe->user->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-lime-50">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center text-sm font-bold text-gray-500 ring-2 ring-lime-50">
                            {{ mb_substr($recipe->user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">{{ __('messages.by') }}</p>
                    <p class="text-sm font-bold text-gray-800">{{ $recipe->user->name }}</p>
                </div>
            </div>

            <a href="{{ route('recipes.show', $recipe) }}"
               class="flex items-center justify-center w-10 h-10 rounded-full bg-lime-50 text-lime-600 hover:bg-lime-500 hover:text-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
