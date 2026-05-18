<x-app-layout>
    <div class="space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ __('messages.my_recipes') }}</h1>
                <p class="text-gray-600">{{ __('messages.manage_culinary_creations') }}</p>
            </div>
            <a href="{{ route('recipes.create') }}" class="bg-lime-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-lime-700 shadow-lg shadow-lime-200/50 transition active:scale-95 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('messages.create_recipe') }}
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-2xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($recipes->isEmpty())
            <div class="bg-white rounded-[2.5rem] p-12 text-center border border-dashed border-gray-300">
                <div class="bg-lime-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('messages.no_recipes_yet') }}</h3>
                <p class="text-gray-500 mb-8">{{ __('messages.share_first_recipe') }}</p>
                <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-2xl shadow-sm text-white bg-lime-600 hover:bg-lime-700">
                    {{ __('messages.get_started') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recipes as $recipe)
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

                            <!-- Status Badge -->
                            <div class="absolute top-5 left-5">
                                @if($recipe->status === 'published')
                                    <span class="backdrop-blur-md bg-green-500/80 text-white text-[10px] uppercase tracking-widest font-bold px-4 py-1.5 rounded-full shadow-sm">
                                        {{ __('messages.published') }}
                                    </span>
                                @else
                                    <span class="backdrop-blur-md bg-yellow-500/80 text-white text-[10px] uppercase tracking-widest font-bold px-4 py-1.5 rounded-full shadow-sm">
                                        {{ __('messages.draft') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Date Overlay -->
                            <div class="absolute bottom-4 left-5">
                                <div class="flex items-center gap-1.5 bg-black/40 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $recipe->created_at->format('d.m.Y') }}
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

                            <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between gap-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('recipes.edit', $recipe) }}"
                                       class="flex items-center justify-center px-4 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 text-sm font-bold">
                                        {{ __('messages.edit') }}
                                    </a>
                                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure_delete') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="flex items-center justify-center px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 text-sm font-bold">
                                            {{ __('messages.delete') }}
                                        </button>
                                    </form>
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
                @endforeach
            </div>

            <div class="mt-12">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

