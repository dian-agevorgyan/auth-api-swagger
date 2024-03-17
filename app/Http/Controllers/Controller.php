<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Info(title="API Documentation", version="1.0.0")
     */
    public function response(array $data = [], int $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        return response()->json($data, $statusCode);
    }
}
