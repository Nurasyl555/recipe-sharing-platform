<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Recipe API",
    version: "1.0.0"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer"
)]
class OpenApi {}