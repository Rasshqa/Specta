<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    /**
     * Build a success response.
     */
    protected function successResponse(mixed $data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Build an error response.
     */
    protected function errorResponse(string $message, int $code = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data'    => $data
        ], $code);
    }
}
