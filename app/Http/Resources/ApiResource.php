<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
/**
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     description="Standard API Response Wrapper"
 * )
 */

class ApiResource
{
    public static function success($data, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public static function error($message = 'Error', $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
        ], $code);
    }
}