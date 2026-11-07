<?php

namespace App\Shared\Http\Traits;

use App\Shared\Http\ApiResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait HasApiResponse
{
    protected function apiSuccess(
        mixed $data = null,
        string $message = 'ดำเนินการสำเร็จ',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::success($data, $message, $statusCode, $meta);
    }

    protected function apiCreated(
        mixed $data = null,
        string $message = 'สร้างข้อมูลเรียบร้อยแล้ว',
        array $meta = []
    ): JsonResponse {
        return ApiResponse::created($data, $message, $meta);
    }

    protected function apiError(
        string $message = 'เกิดข้อผิดพลาดในการประมวลผล',
        int $statusCode = 400,
        mixed $errors = null,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::error($message, $statusCode, $errors, $meta);
    }

    protected function apiPaginated(
        LengthAwarePaginator $paginator,
        string $message = 'ดึงข้อมูลสำเร็จ',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::paginated($paginator, $message, $statusCode, $meta);
    }
}
