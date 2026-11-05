<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($e instanceof ValidationException) {
                return \App\Shared\Http\ApiResponse::error(
                    message: $e->getMessage() ?: 'ข้อมูลที่ส่งมาไม่ถูกต้อง',
                    statusCode: $e->status,
                    errors: $e->errors()
                );
            }

            if ($e instanceof AuthenticationException) {
                return \App\Shared\Http\ApiResponse::error(
                    message: 'กรุณายืนยันตัวตนก่อนเข้าใช้งาน (Unauthenticated)',
                    statusCode: 401
                );
            }

            if ($e instanceof AuthorizationException) {
                return \App\Shared\Http\ApiResponse::error(
                    message: $e->getMessage() ?: 'ไม่มีสิทธิ์เข้าถึงข้อมูลส่วนนี้ (Forbidden)',
                    statusCode: 403
                );
            }

            if ($e instanceof ModelNotFoundException) {
                return \App\Shared\Http\ApiResponse::error(
                    message: 'ไม่พบข้อมูลที่ระบุในระบบ (Resource Not Found)',
                    statusCode: 404
                );
            }

            if ($e instanceof HttpExceptionInterface) {
                return \App\Shared\Http\ApiResponse::error(
                    message: $e->getMessage() ?: 'เกิดข้อผิดพลาดในการประมวลผล',
                    statusCode: $e->getStatusCode()
                );
            }
        }

        return parent::render($request, $e);
    }
}
