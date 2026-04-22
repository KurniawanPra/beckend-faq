<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Return a success JSON response.
     */
    protected function success(mixed $data = null, string $message = '', int $status = 200, array $meta = []): \Illuminate\Http\JsonResponse
    {
        $response = ['success' => true];

        if ($message) {
            $response['message'] = $message;
        }

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }

    /**
     * Return an error JSON response.
     */
    protected function error(string $message, int $status = 400, mixed $errors = null): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'error'   => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
