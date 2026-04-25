<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'recipe_id' => $this->recipe_id,
            'created_at' => $this->created_at,
        ];
    }
}