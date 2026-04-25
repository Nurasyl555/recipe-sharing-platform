<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Добавить новый рецепт') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong>Ой! В форме есть ошибки:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Название рецепта</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Краткое описание</label>
                            <textarea name="description" id="description" rows="2" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Минимум 10 символов</p>
                        </div>

                        <div class="mb-4">
                            <label for="instructions" class="block text-sm font-medium text-gray-700">Инструкция по приготовлению</label>
                            <textarea name="instructions" id="instructions" rows="5" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('instructions') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Минимум 20 символов</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="prep_time" class="block text-sm font-medium text-gray-700">Подготовка (мин)</label>
                                <input type="number" name="prep_time" id="prep_time" value="{{ old('prep_time') }}" required min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="cook_time" class="block text-sm font-medium text-gray-700">Готовка (мин)</label>
                                <input type="number" name="cook_time" id="cook_time" value="{{ old('cook_time') }}" required min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="servings" class="block text-sm font-medium text-gray-700">Порции</label>
                                <input type="number" name="servings" id="servings" value="{{ old('servings') }}" required min="1"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-700">Сложность</label>
                                <select name="difficulty" id="difficulty" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="easy" @selected(old('difficulty') == 'easy')>Легко (Easy)</option>
                                    <option value="medium" @selected(old('difficulty') == 'medium')>Средне (Medium)</option>
                                    <option value="hard" @selected(old('difficulty') == 'hard')>Сложно (Hard)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Категория</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="cuisine_id" class="block text-sm font-medium text-gray-700">Кухня</label>
                                <select name="cuisine_id" id="cuisine_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($cuisines as $cuisine)
                                        <option value="{{ $cuisine->id }}" @selected(old('cuisine_id') == $cuisine->id)>{{ $cuisine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Фотография блюда (необязательно)</label>
                            <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <div class="mb-6 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ингредиенты</h3>
                            <div id="ingredients-container">
                                <div class="flex space-x-2 mb-2 ingredient-row">
                                    <input type="text" name="ingredients[]" placeholder="Название (например: Молоко)" required
                                           class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <input type="text" name="amounts[]" placeholder="Кол-во (напр: 200 мл)" required
                                           class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <button type="button" id="add-ingredient" class="mt-2 text-sm text-indigo-600 hover:text-indigo-900">+ Добавить ингредиент</button>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
                                Сохранить рецепт
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const row = document.createElement('div');
            row.className = 'flex space-x-2 mb-2 ingredient-row';
            row.innerHTML = `
                <input type="text" name="ingredients[]" placeholder="Название" required class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="amounts[]" placeholder="Кол-во" required class="w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" class="text-red-500 px-2 font-bold remove-ingredient">&times;</button>
            `;
            container.appendChild(row);
        });

        document.getElementById('ingredients-container').addEventListener('click', function(e) {
            if(e.target.classList.contains('remove-ingredient')) {
                e.target.parentElement.remove();
            }
        });
    </script>
</x-app-layout>
