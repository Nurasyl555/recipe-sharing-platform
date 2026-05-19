<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    #[OA\Post(
        path: "/auth/register",
        operationId: "register",
        tags: ["Auth"],
        summary: "Register new user",
        description: "Create a new user account",
        requestBody: new OA\RequestBody(
            required: true,
            description: "User registration data",
            content: new OA\JsonContent(
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe", maxLength: 255),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", minLength: 6, example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "User registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                        ], type: "object"),
                        new OA\Property(property: "message", type: "string", example: "User registered successfully"),
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation failed"),
        ]
    )]
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return ApiResource::success(
            new UserResource($user),
            'User registered successfully',
            201
        );
    }

    #[OA\Post(
        path: "/auth/login",
        operationId: "login",
        tags: ["Auth"],
        summary: "User login",
        description: "Authenticate user and get API token",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Login credentials",
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", properties: [
                            new OA\Property(property: "token", type: "string", description: "API Bearer token"),
                            new OA\Property(property: "user", properties: [
                                new OA\Property(property: "id", type: "integer"),
                                new OA\Property(property: "name", type: "string"),
                                new OA\Property(property: "email", type: "string"),
                            ], type: "object"),
                        ], type: "object"),
                        new OA\Property(property: "message", type: "string", example: "Login successful"),
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Invalid credentials"),
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResource::error('Invalid credentials', 401);
        }

        return ApiResource::success(
            [
                'user' => new UserResource($user),
                'token' => $user->createToken('auth_token')->plainTextToken
            ],
            'Login successful',
            200
        );
    }
}