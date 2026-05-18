<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

class SwaggerController extends Controller
{
    #[OA\Get(
        path: "/api/health",
        operationId: "health",
        tags: ["Health"],
        summary: "System health check",
        responses: [
            new OA\Response(
                response: 200,
                description: "System is healthy"
            )
        ]
    )]
    public function health()
    {
        return response()->json(['status' => 'ok', 'timestamp' => now()]);
    }
}