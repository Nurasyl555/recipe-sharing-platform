<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Rating",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
 *     @OA\Property(property="comment", type="string", nullable=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="recipe_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class RatingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'recipe_id' => $this->recipe_id,
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
        ];
    }
}