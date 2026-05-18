<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Recipe Sharing Platform API",
 *     version="1.0.0",
 *     description="API для платформы обмена рецептами",
 *     @OA\Contact(email="support@recipes.com")
 * )
 * @OA\Server(url="http://127.0.0.1:8000/api", description="Development")
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     name="token",
 *     in="header",
 *     securityScheme="bearerAuth"
 * )
 */
class SwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/health",
     *     summary="Health check",
     *     tags={"System"},
     *     @OA\Response(response=200, description="API is healthy")
     * )
     */
    public function health()
    {
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(required=true),
     *     @OA\Response(response=201, description="User registered")
     * )
     */
    public function register()
    {
    }
}