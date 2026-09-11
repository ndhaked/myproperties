<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    public static function error(string $code, string $message, int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ], $status);
    }

    /**
     * Transform a paginator into the spec's {items, pagination} shape.
     */
    public static function paginated(LengthAwarePaginator $paginator, ?callable $itemTransform = null): array
    {
        $items = $itemTransform
            ? $paginator->getCollection()->map($itemTransform)->all()
            : $paginator->items();

        return [
            'items' => $items,
            'pagination' => [
                'page' => $paginator->currentPage(),
                'limit' => $paginator->perPage(),
                'totalItems' => $paginator->total(),
                'totalPages' => $paginator->lastPage(),
            ],
        ];
    }
}
