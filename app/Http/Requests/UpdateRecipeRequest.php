<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $recipe = $this->route('recipe');
        return auth()->check() &&
            (auth()->user()->isAdmin() || $recipe->isOwnedBy(auth()->user()));
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'min:3', 'max:255'],
            'description'   => ['required', 'string', 'min:10'],
            'instructions'  => ['required', 'string', 'min:20'],
            'prep_time'     => ['required', 'integer', 'min:1'],
            'cook_time'     => ['required', 'integer', 'min:1'],
            'servings'      => ['required', 'integer', 'min:1'],
            'difficulty'    => ['required', 'in:easy,medium,hard'],
            'category_id'   => ['required', 'exists:categories,id'],
            'cuisine_id'    => ['required', 'exists:cuisines,id'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ingredients'   => ['required', 'array', 'min:1'],
            'ingredients.*' => ['required', 'string'],
            'amounts'       => ['required', 'array', 'min:1'],
            'amounts.*'     => ['required', 'string'],
        ];
    }
}
