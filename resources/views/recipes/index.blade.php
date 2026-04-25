<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Все Рецепты') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($recipes as $recipe)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold mb-2">{{ $recipe->title }}</h3>

                            <p class="text-sm text-gray-600 mb-4">
                                {{ Str::limit($recipe->description, 100) }}
                            </p>

                            <div class="flex justify-between items-center text-sm text-gray-500">
                                <span>Время: {{ $recipe->prep_time }} мин.</span>
                            </div>

                            <div class="mt-4">
                                <a href="#" class="text-blue-500 hover:underline">Смотреть рецепт &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            @if($recipes->isEmpty())
                <div class="text-center text-gray-500 py-8">
                    Рецепты пока не добавлены.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
