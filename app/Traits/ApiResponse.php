<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
        ], $code);
    }

    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
        ], 422);
    }

    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    protected function createdResponse($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    protected function updatedResponse($data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 200);
    }

    protected function deletedResponse(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return $this->successResponse(null, $message, 200);
    }

    protected function paginatedResponse($data, string $message = 'Data retrieved successfully'): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                ],
                'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
            ]);
        }

        return $this->successResponse($data, $message);
    }

    protected function resourceResponse(JsonResource $resource, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resource,
            'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
        ]);
    }

    protected function resourceCollectionResponse(ResourceCollection $collection, string $message = 'Data retrieved successfully'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection,
            'timestamp' => now()->setTimezone('Asia/Riyadh')->format('Y-m-d\TH:i:s.v\Z'),
        ]);
    }

    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function conflictResponse(string $message = 'Conflict occurred'): JsonResponse
    {
        return $this->errorResponse($message, 409);
    }

    protected function tooManyRequestsResponse(string $message = 'Too many requests'): JsonResponse
    {
        return $this->errorResponse($message, 429);
    }

    protected function serviceUnavailableResponse(string $message = 'Service temporarily unavailable'): JsonResponse
    {
        return $this->errorResponse($message, 503);
    }
}
