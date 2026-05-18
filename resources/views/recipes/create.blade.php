<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-lime-900 leading-tight">
            {{ __('Add New Recipe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl shadow-lime-200/50 sm:rounded-[2.5rem] border border-lime-100">
                <div class="p-10 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl">
                            <strong class="font-bold block mb-2 text-lg">Oops! There are some errors:</strong>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="space-y-2">
                            <label for="title" class="block text-sm font-bold text-lime-800">Recipe Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-bold text-lime-800">Short Description</label>
                            <textarea name="description" id="description" rows="2" required
                                      class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">{{ old('description') }}</textarea>
                            <p class="text-xs text-lime-600 mt-1 italic">Minimum 10 characters</p>
                        </div>

                        <div class="space-y-2">
                            <label for="instructions" class="block text-sm font-bold text-lime-800">Cooking Instructions</label>
                            <textarea name="instructions" id="instructions" rows="5" required
                                      class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">{{ old('instructions') }}</textarea>
                            <p class="text-xs text-lime-600 mt-1 italic">Minimum 20 characters</p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="space-y-2">
                                <label for="prep_time" class="block text-sm font-bold text-lime-800">Prep (min)</label>
                                <input type="number" name="prep_time" id="prep_time" value="{{ old('prep_time') }}" required min="1"
                                       class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                            </div>

                            <div class="space-y-2">
                                <label for="cook_time" class="block text-sm font-bold text-lime-800">Cook (min)</label>
                                <input type="number" name="cook_time" id="cook_time" value="{{ old('cook_time') }}" required min="1"
                                       class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                            </div>

                            <div class="space-y-2">
                                <label for="servings" class="block text-sm font-bold text-lime-800">Servings</label>
                                <input type="number" name="servings" id="servings" value="{{ old('servings') }}" required min="1"
                                       class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                            </div>

                            <div class="space-y-2">
                                <label for="difficulty" class="block text-sm font-bold text-lime-800">Difficulty</label>
                                <select name="difficulty" id="difficulty" required class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                                    <option value="easy" @selected(old('difficulty') == 'easy')>Easy</option>
                                    <option value="medium" @selected(old('difficulty') == 'medium')>Medium</option>
                                    <option value="hard" @selected(old('difficulty') == 'hard')>Hard</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="category_id" class="block text-sm font-bold text-lime-800">Category</label>
                                <select name="category_id" id="category_id" class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="cuisine_id" class="block text-sm font-bold text-lime-800">Cuisine</label>
                                <select name="cuisine_id" id="cuisine_id" class="block w-full rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                                    @foreach($cuisines as $cuisine)
                                        <option value="{{ $cuisine->id }}" @selected(old('cuisine_id') == $cuisine->id)>{{ $cuisine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="image" class="block text-sm font-bold text-lime-800">Dish Photo (Optional)</label>
                            <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-lime-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100 transition duration-150 cursor-pointer">
                        </div>

                        <div class="border-t border-lime-100 pt-8">
                            <h3 class="text-xl font-bold text-lime-900 mb-6 flex items-center gap-2">
                                <span class="w-8 h-8 bg-lime-100 text-lime-600 rounded-lg flex items-center justify-center text-sm">🥗</span>
                                Ingredients
                            </h3>
                            <div id="ingredients-container" class="space-y-4">
                                <div class="flex space-x-3 ingredient-row">
                                    <input type="text" name="ingredients[]" placeholder="Name (e.g. Milk)" required
                                           class="flex-1 rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                                    <input type="text" name="amounts[]" placeholder="Amount (e.g. 200ml)" required
                                           class="w-1/3 rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                                </div>
                            </div>
                            <button type="button" id="add-ingredient" class="mt-6 inline-flex items-center text-sm font-bold text-lime-600 hover:text-lime-800 transition duration-150">
                                <span class="mr-2 text-xl">+</span> Add Ingredient
                            </button>
                        </div>

                        <div class="flex justify-end pt-8 border-t border-lime-100">
                            <button type="submit" class="bg-lime-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 shadow-lg shadow-lime-200/50 transition duration-150 active:scale-95">
                                Save Recipe
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
            row.className = 'flex space-x-3 ingredient-row';
            row.innerHTML = `
                <input type="text" name="ingredients[]" placeholder="Name" required class="flex-1 rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                <input type="text" name="amounts[]" placeholder="Amount" required class="w-1/3 rounded-xl border-lime-200 shadow-sm focus:border-lime-500 focus:ring-lime-500 py-3 px-4 transition duration-150">
                <button type="button" class="text-red-500 px-3 font-black remove-ingredient hover:text-red-700 transition duration-150">&times;</button>
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
