<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'prep_time' => $this->prep_time,
            'cook_time' => $this->cook_time,
            'servings' => $this->servings,
            'difficulty' => $this->difficulty,
            'status' => $this->status,
            'image' => $this->image,
            'user' => new UserResource($this->whenLoaded('user')),
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'cuisine' => $this->whenLoaded('cuisine', function () {
                return [
                    'id' => $this->cuisine->id,
                    'name' => $this->cuisine->name,
                ];
            }),
            'ingredients' => IngredientResource::collection($this->whenLoaded('ingredients')),
            'avg_rating' => $this->whenLoaded('ratings') ? round((float) $this->ratings->avg('rating'), 2) : null,
            'ratings_count' => $this->whenLoaded('ratings') ? $this->ratings->count() : 0,
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
        ];
    }
}