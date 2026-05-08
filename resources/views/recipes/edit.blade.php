<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Recipe') }}: {{ $recipe->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="title" :value="__('Recipe Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $recipe->title)" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Short Description')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">{{ old('description', $recipe->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="prep_time" :value="__('Prep Time (min)')" />
                                    <x-text-input id="prep_time" name="prep_time" type="number" class="mt-1 block w-full" :value="old('prep_time', $recipe->prep_time)" required />
                                </div>
                                <div>
                                    <x-input-label for="cook_time" :value="__('Cook Time (min)')" />
                                    <x-text-input id="cook_time" name="cook_time" type="number" class="mt-1 block w-full" :value="old('cook_time', $recipe->cook_time)" required />
                                </div>
                                <div>
                                    <x-input-label for="servings" :value="__('Servings')" />
                                    <x-text-input id="servings" name="servings" type="number" class="mt-1 block w-full" :value="old('servings', $recipe->servings)" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" />
                                    <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $recipe->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="difficulty" :value="__('Difficulty')" />
                                    <select name="difficulty" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="easy" {{ $recipe->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ $recipe->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ $recipe->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <x-input-label :value="__('Recipe Image')" />
                                @if($recipe->image)
                                    <img src="{{ asset('storage/' . $recipe->image) }}" class="h-32 w-full object-cover rounded-lg mb-2">
                                @endif
                                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" />
                            </div>

                            <div>
                                <x-input-label for="instructions" :value="__('Instructions')" />
                                <textarea id="instructions" name="instructions" rows="6" class="mt-1 block w-full border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm">{{ old('instructions', $recipe->instructions) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ingredients</h3>
                        <div id="ingredients-container" class="space-y-3">
                            @foreach($recipe->ingredients as $index => $ingredient)
                                <div class="flex items-center space-x-3 ingredient-row">
                                    <x-text-input name="ingredients[]" type="text" placeholder="Ingredient name" class="flex-1" :value="$ingredient->name" required />
                                    <x-text-input name="amounts[]" type="text" placeholder="Amount (e.g. 200g)" class="w-1/3" :value="$ingredient->pivot->amount" required />
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-ingredient" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            + Add Ingredient
                        </button>
                    </div>

                    <div class="flex justify-end pt-6 border-t">
                        <x-primary-button>Update Recipe</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-ingredient').addEventListener('click', function() {
            const container = document.getElementById('ingredients-container');
            const row = document.createElement('div');
            row.className = 'flex items-center space-x-3 ingredient-row';
            row.innerHTML = `
                <input type="text" name="ingredients[]" placeholder="Ingredient name" class="flex-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm" required>
                <input type="text" name="amounts[]" placeholder="Amount (e.g. 200g)" class="w-1/3 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
            `;
            container.appendChild(row);
        });
    </script>
</x-app-layout>
