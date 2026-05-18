<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-lime-900 leading-tight">
            {{ __('Edit Recipe') }}: {{ $recipe->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl shadow-lime-200/50 sm:rounded-[2.5rem] border border-lime-100 p-10">
                <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="title" :value="__('Recipe Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full py-3 px-4" :value="old('title', $recipe->title)" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Short Description')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-lime-200 focus:border-lime-500 focus:ring-lime-500 rounded-xl shadow-sm py-3 px-4">{{ old('description', $recipe->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="prep_time" :value="__('Prep (min)')" />
                                    <x-text-input id="prep_time" name="prep_time" type="number" class="mt-1 block w-full py-3 px-4" :value="old('prep_time', $recipe->prep_time)" required />
                                </div>
                                <div>
                                    <x-input-label for="cook_time" :value="__('Cook (min)')" />
                                    <x-text-input id="cook_time" name="cook_time" type="number" class="mt-1 block w-full py-3 px-4" :value="old('cook_time', $recipe->cook_time)" required />
                                </div>
                                <div>
                                    <x-input-label for="servings" :value="__('Servings')" />
                                    <x-text-input id="servings" name="servings" type="number" class="mt-1 block w-full py-3 px-4" :value="old('servings', $recipe->servings)" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" />
                                    <select name="category_id" id="category_id" class="mt-1 block w-full border-lime-200 rounded-xl shadow-sm py-3 px-4 focus:border-lime-500 focus:ring-lime-500">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $recipe->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="cuisine_id" :value="__('Cuisine')" />
                                    <select name="cuisine_id" id="cuisine_id" class="mt-1 block w-full border-lime-200 rounded-xl shadow-sm py-3 px-4 focus:border-lime-500 focus:ring-lime-500">
                                        @foreach($cuisines as $cuisine)
                                            <option value="{{ $cuisine->id }}" {{ $recipe->cuisine_id == $cuisine->id ? 'selected' : '' }}>{{ $cuisine->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('cuisine_id')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="difficulty" :value="__('messages.difficulty')" />
                                    <select name="difficulty" id="difficulty" class="mt-1 block w-full border-lime-200 rounded-xl shadow-sm py-3 px-4 focus:border-lime-500 focus:ring-lime-500">
                                        <option value="easy" {{ $recipe->difficulty == 'easy' ? 'selected' : '' }}>{{ __('messages.easy') }}</option>
                                        <option value="medium" {{ $recipe->difficulty == 'medium' ? 'selected' : '' }}>{{ __('messages.medium') }}</option>
                                        <option value="hard" {{ $recipe->difficulty == 'hard' ? 'selected' : '' }}>{{ __('messages.hard') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('difficulty')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="image" :value="__('messages.recipe_image')" />
                                @if($recipe->image)
                                    <div class="relative rounded-2xl overflow-hidden mb-3 border-2 border-lime-100 shadow-sm">
                                        <img src="{{ asset('storage/' . $recipe->image) }}" class="h-48 w-full object-cover">
                                        <div class="absolute inset-0 bg-black/10"></div>
                                    </div>
                                @endif
                                <input id="image" type="file" name="image" class="block w-full text-sm text-lime-600 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:bg-lime-50 file:text-lime-700 hover:file:bg-lime-100 transition duration-150 cursor-pointer" />
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="instructions" :value="__('messages.instructions')" />
                                <textarea id="instructions" name="instructions" rows="8" class="mt-1 block w-full border-lime-200 focus:border-lime-500 focus:ring-lime-500 rounded-xl shadow-sm py-3 px-4">{{ old('instructions', $recipe->instructions) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 border-t border-lime-100 pt-8">
                        <h3 class="text-xl font-bold text-lime-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-lime-100 text-lime-600 rounded-lg flex items-center justify-center text-sm">🥗</span>
                            {{ __('messages.ingredients') }}
                        </h3>
                        <div id="ingredients-container" class="space-y-4">
                            @foreach($recipe->ingredients as $index => $ingredient)
                                <div class="flex items-center space-x-3 ingredient-row">
                                    <x-text-input name="ingredients[]" type="text" placeholder="{{ __('messages.ingredient_name') }}" aria-label="{{ __('messages.ingredient_name') }}" class="flex-1 py-3 px-4" :value="$ingredient->name" required />
                                    <x-text-input name="amounts[]" type="text" placeholder="{{ __('messages.amount_placeholder') }}" aria-label="{{ __('messages.amount_placeholder') }}" class="w-1/3 py-3 px-4" :value="$ingredient->pivot->amount" required />
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 transition duration-150 px-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-ingredient" class="mt-6 inline-flex items-center text-sm font-bold text-lime-600 hover:text-lime-800 transition duration-150">
                            <span class="mr-2 text-xl">+</span> {{ __('messages.add_ingredient') }}
                        </button>
                    </div>

                    <div class="flex justify-end pt-8 border-t border-lime-100">
                        <x-primary-button class="px-8 py-4 shadow-lg shadow-lime-200/50">{{ __('messages.update_recipe') }}</x-primary-button>
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
                <input type="text" name="ingredients[]" placeholder="{{ __('messages.ingredient_name') }}" aria-label="{{ __('messages.ingredient_name') }}" class="flex-1 border-lime-200 focus:border-lime-500 focus:ring-lime-500 rounded-xl shadow-sm py-3 px-4 transition duration-150" required>
                <input type="text" name="amounts[]" placeholder="{{ __('messages.amount_placeholder') }}" aria-label="{{ __('messages.amount_placeholder') }}" class="w-1/3 border-lime-200 focus:border-lime-500 focus:ring-lime-500 rounded-xl shadow-sm py-3 px-4 transition duration-150" required>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 transition duration-150 px-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
            `;
            container.appendChild(row);
        });
    </script>
</x-app-layout>
