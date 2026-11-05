<?php

namespace App\Shared\Http;

use App\Shared\Tenancy\TenantContext;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public const API_VERSION = 'v1';

    /**
     * Standard response envelope generator.
     */
    public static function format(
        bool $success,
        string $message,
        mixed $data = null,
        mixed $errors = null,
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $defaultMeta = [
            'timestamp' => Carbon::now()->toISOString(),
            'org_id'    => TenantContext::currentOrgId(),
            'version'   => self::API_VERSION,
        ];

        $payload = [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
            'meta'    => array_merge($defaultMeta, $meta),
        ];

        return new JsonResponse($payload, $statusCode, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return a standardized success (200 OK) JSON response.
     */
    public static function success(
        mixed $data = null,
        string $message = 'ดำเนินการสำเร็จ',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        return self::format(
            success: true,
            message: $message,
            data: $data,
            errors: null,
            statusCode: $statusCode,
            meta: $meta
        );
    }

    /**
     * Return a standardized created (201 Created) JSON response.
     */
    public static function created(
        mixed $data = null,
        string $message = 'สร้างข้อมูลเรียบร้อยแล้ว',
        array $meta = []
    ): JsonResponse {
        return self::format(
            success: true,
            message: $message,
            data: $data,
            errors: null,
            statusCode: 201,
            meta: $meta
        );
    }

    /**
     * Return a standardized error JSON response.
     */
    public static function error(
        string $message = 'เกิดข้อผิดพลาดในการประมวลผล',
        int $statusCode = 400,
        mixed $errors = null,
        array $meta = []
    ): JsonResponse {
        return self::format(
            success: false,
            message: $message,
            data: null,
            errors: $errors,
            statusCode: $statusCode,
            meta: $meta
        );
    }

    /**
     * Return a standardized paginated JSON response.
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'ดึงข้อมูลสำเร็จ',
        int $statusCode = 200,
        array $meta = []
    ): JsonResponse {
        $paginationMeta = [
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];

        return self::format(
            success: true,
            message: $message,
            data: $paginator->items(),
            errors: null,
            statusCode: $statusCode,
            meta: array_merge($paginationMeta, $meta)
        );
    }

    /**
     * Return 204 No Content.
     */
    public static function noContent(): JsonResponse
    {
        return new JsonResponse(null, 204);
    }
}
