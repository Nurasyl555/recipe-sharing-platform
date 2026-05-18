<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
//        return auth()->check();
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'min:3', 'max:255'],
            'description'    => ['required', 'string', 'min:10'],
            'instructions'   => ['required', 'string', 'min:20'],
            'prep_time'      => ['required', 'integer', 'min:1'],
            'cook_time'      => ['required', 'integer', 'min:1'],
            'servings'       => ['required', 'integer', 'min:1'],
            'difficulty'     => ['required', 'in:easy,medium,hard'],
            'category_id'    => ['required', 'exists:categories,id'],
            'cuisine_id'     => ['required', 'exists:cuisines,id'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ingredients'    => ['required', 'array', 'min:1'],
            'ingredients.*'  => ['required', 'string'],
            'amounts'        => ['required', 'array', 'min:1'],
            'amounts.*'      => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Recipe title is required.',
            'ingredients.required' => 'Add at least one ingredient.',
            'category_id.exists'   => 'Selected category does not exist.',
            'cuisine_id.exists'    => 'Selected cuisine does not exist.',
            'image.max'            => 'Image must be less than 2MB.',
        ];
    }
}
