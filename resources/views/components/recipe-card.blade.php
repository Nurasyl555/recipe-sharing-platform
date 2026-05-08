@props(['recipe'])

<div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
    <div class="relative h-48 w-full overflow-hidden">
        @if($recipe->image)
            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        @endif

        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-orange-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
            {{ $recipe->cuisine->name ?? 'World' }}
        </div>

        <div class="absolute top-4 right-4 bg-gray-900/70 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ ucfirst($recipe->difficulty) }}
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-gray-900 line-clamp-1">
                <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-500 transition-colors">
                    {{ $recipe->title }}
                </a>
            </h3>
        </div>

        <p class="text-gray-500 text-sm mb-4 line-clamp-2 flex-1">
            {{ $recipe->description }}
        </p>

        <div class="flex items-center text-sm text-gray-600 mb-4 space-x-4">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $recipe->prep_time + $recipe->cook_time }} min
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $recipe->servings }} serv
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                @if($recipe->user->avatar)
                    <img src="{{ asset('storage/' . $recipe->user->avatar) }}" alt="{{ $recipe->user->name }}" class="w-6 h-6 rounded-full mr-2">
                @else
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 mr-2">
                        {{ substr($recipe->user->name, 0, 1) }}
                    </div>
                @endif
                <span class="text-sm font-medium text-gray-700">{{ $recipe->user->name }}</span>
            </div>
            <a href="{{ route('recipes.show', $recipe) }}" class="text-orange-500 hover:text-orange-600 text-sm font-semibold flex items-center transition">
                View
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</div>
