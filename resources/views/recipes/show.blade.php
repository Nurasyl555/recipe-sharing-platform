<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $recipe->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-1/3">
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="rounded-lg shadow-md w-full object-cover h-64">
                        </div>

                        <div class="w-full md:w-2/3">
                            <h1 class="text-3xl font-bold mb-2">{{ $recipe->title }}</h1>
                            <p class="text-gray-600 mb-4">{{ $recipe->description }}</p>

                            <div class="flex flex-wrap gap-4 mb-6">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">Категория: {{ $recipe->category->name ?? 'Не указана' }}</span>
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">Кухня: {{ $recipe->cuisine->name ?? 'Не указана' }}</span>
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">Время: {{ $recipe->prep_time + $recipe->cook_time }} мин.</span>
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">Сложность: {{ ucfirst($recipe->difficulty) }}</span>
                            </div>

                            <h3 class="text-xl font-semibold mb-2">Ингредиенты:</h3>
                            <ul class="list-disc list-inside mb-6">
                                @forelse($recipe->ingredients as $ingredient)
                                    <li>{{ $ingredient->name }} — {{ $ingredient->pivot->amount }}</li>
                                @empty
                                    <li class="text-gray-500">Ингредиенты не указаны</li>
                                @endforelse
                            </ul>

                            <h3 class="text-xl font-semibold mb-2">Инструкция:</h3>
                            <div class="prose max-w-none">
                                {!! nl2br(e($recipe->instructions)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 border-t pt-6">
                        <h3 class="text-2xl font-bold mb-4">Отзывы ({{ $recipe->ratings->count() }})</h3>
                        <p class="text-gray-500">Средняя оценка: {{ $recipe->average_rating }} ⭐</p>

                        @forelse($recipe->ratings as $rating)
                            <div class="mt-4 p-4 border rounded">
                                <div class="font-bold">{{ $rating->user->name ?? 'Пользователь' }} <span class="text-yellow-500">{{ $rating->score }}⭐</span></div>
                                <p class="text-gray-700 mt-2">{{ $rating->comment }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 mt-4">Пока нет отзывов.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
