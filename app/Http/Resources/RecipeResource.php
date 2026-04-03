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
            'description' => $this->description,
            'cuisine' => $this->cuisine,
            'prep_time' => $this->prep_time,
            'user' => new UserResource($this->whenLoaded('user')),
            'avg_rating' => $this->whenLoaded('ratings') ? $this->ratings->avg('score') : null,
            'ratings_count' => $this->whenLoaded('ratings') ? $this->ratings->count() : 0,
            'created_at' => $this->created_at,
        ];
    }
}