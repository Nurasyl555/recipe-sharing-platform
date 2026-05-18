<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="Authentication", description="User authentication endpoints")
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Register new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", minLength=8),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="token", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="User registered")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     )
     * )
     */
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
            ]);
        } catch (ValidationException $e) {
            return ApiResource::error('Validation failed', 422, $e->errors());
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResource::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ], 'User registered', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string")
     *             ),
     *             @OA\Property(property="message", type="string", example="Login successful")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);
        } catch (ValidationException $e) {
            return ApiResource::error('Validation failed', 422, $e->errors());
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return ApiResource::error('Invalid credentials', 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResource::success([
            'token' => $token,
        ], 'Login successful', 200);
    }
}