<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Standard success JSON response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = [], ?string $message = 'data retrieved.', int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($payload, $status);
    }

    /**
     * Standard error JSON response.
     *
     * @param string|null $message
     * @param array|null $errors
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(?string $message = 'error happened', ?array $data = [], int $status = 400): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($payload, $status);
    }
}
