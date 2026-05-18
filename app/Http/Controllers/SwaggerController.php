<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Recipe Sharing Platform API",
 *     version="1.0.0",
 *     description="API for recipe sharing and discovery"
 * )
 * @OA\Server(url="http://127.0.0.1:8000/api", description="Development Server")
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     name="token",
 *     in="header",
 *     securityScheme="bearerAuth"
 * )
 *
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean"),
 *     @OA\Property(property="data", type="object", nullable=true),
 *     @OA\Property(property="message", type="string")
 * )
 *
 * @OA\Schema(
 *     schema="ApiErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="data", type="null"),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="errors", type="object", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="Recipe",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="slug", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="instructions", type="string"),
 *     @OA\Property(property="prep_time", type="integer"),
 *     @OA\Property(property="cook_time", type="integer"),
 *     @OA\Property(property="servings", type="integer"),
 *     @OA\Property(property="difficulty", type="string"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="image", type="string", nullable=true),
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="category", ref="#/components/schemas/Category"),
 *     @OA\Property(property="cuisine", ref="#/components/schemas/Cuisine"),
 *     @OA\Property(property="ingredients", type="array", @OA\Items(ref="#/components/schemas/Ingredient")),
 *     @OA\Property(property="avg_rating", type="number", format="float", nullable=true),
 *     @OA\Property(property="ratings_count", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Ingredient",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="amount", type="string", nullable=true),
 *     @OA\Property(property="unit", type="string", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string", nullable=true),
 *     @OA\Property(property="description", type="string", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="Cuisine",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="slug", type="string", nullable=true),
 *     @OA\Property(property="country", type="string", nullable=true)
 * )
 */
class SwaggerController extends Controller
{
}