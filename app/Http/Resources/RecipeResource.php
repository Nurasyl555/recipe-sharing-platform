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
            'image' => $this->image,
            'status' => $this->status,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'cuisine' => new CuisineResource($this->whenLoaded('cuisine')),
            'user' => new UserResource($this->whenLoaded('user')),
            'avg_rating' => $this->whenLoaded('ratings') ? $this->ratings->avg('score') : null,
            'ratings_count' => $this->whenLoaded('ratings') ? $this->ratings->count() : 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
