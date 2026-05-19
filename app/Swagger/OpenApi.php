<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Recipe Sharing Platform API",
    version: "1.0.0",
    description: "API для платформы обмена рецептами с поддержкой аутентификации, оценок и избранного",
    contact: new OA\Contact(
        name: "Support",
        email: "support@recipes.local"
    ),
    license: new OA\License(name: "MIT")
)]
#[OA\Server(
    url: "http://localhost:8000/api",
    description: "Development Server"
)]

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    description: "Sanctum API token"
)]
#[OA\Tag(name: "Auth", description: "Authentication endpoints")]
#[OA\Tag(name: "Recipes", description: "Recipe CRUD operations")]
#[OA\Tag(name: "Ratings", description: "Recipe ratings")]
#[OA\Tag(name: "Favorites", description: "Favorite recipes")]
class OpenApi {}