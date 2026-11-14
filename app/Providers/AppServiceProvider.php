<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin\Organization;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $helperFile = app_path('Helpers/helpers.php');
        if (file_exists($helperFile)) {
            require_once $helperFile;
        }

        // Spatie Permission v5/v6 forward compatibility aliases
        if (!class_exists('\Spatie\Permission\Middleware\RoleMiddleware') && class_exists('\Spatie\Permission\Middlewares\RoleMiddleware')) {
            class_alias('\Spatie\Permission\Middlewares\RoleMiddleware', '\Spatie\Permission\Middleware\RoleMiddleware');
            class_alias('\Spatie\Permission\Middlewares\PermissionMiddleware', '\Spatie\Permission\Middleware\PermissionMiddleware');
            class_alias('\Spatie\Permission\Middlewares\RoleOrPermissionMiddleware', '\Spatie\Permission\Middleware\RoleOrPermissionMiddleware');
        }

        // Case-insensitive PSR-4 fallback for Linux environments (e.g. Keptkaya -> KeptKaya, KpBankAccount -> KPBankAccount)
        spl_autoload_register(function (string $class): void {
            if (!str_starts_with($class, 'App\\')) {
                return;
            }
            $normalized = str_replace(
                ['App\\Models\\Keptkaya\\', 'App\\Http\\Controllers\\Keptkaya\\', 'App\\Models\\KpBankAccount'],
                ['App\\Models\\KeptKaya\\', 'App\\Http\\Controllers\\KeptKaya\\', 'App\\Models\\KPBankAccount'],
                $class
            );
            $relativePath = str_replace('\\', '/', substr($normalized, 4)) . '.php';
            $fullPath = app_path($relativePath);
            if (file_exists($fullPath)) {
                require_once $fullPath;
            }
        }, true, false);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null;
        });

        // Standard API Response Macros (ADR-0008)
        \Illuminate\Support\Facades\Response::macro('apiSuccess', function ($data = null, string $message = 'ดำเนินการสำเร็จ', int $status = 200, array $meta = []) {
            return \App\Shared\Http\ApiResponse::success($data, $message, $status, $meta);
        });

        \Illuminate\Support\Facades\Response::macro('apiError', function (string $message = 'เกิดข้อผิดพลาดในการประมวลผล', int $status = 400, $errors = null, array $meta = []) {
            return \App\Shared\Http\ApiResponse::error($message, $status, $errors, $meta);
        });

        // ส่งตัวแปร $orgInfos ไปยัง Layout หลักอัตโนมัติ
        View::composer('layouts.admin1', function ($view) {
            $orgInfos = [];
            if (Auth::check()) {
                $user = Auth::user();
                $orgId = getAuthOrgId() ?? $user->org_id_fk;
                $orgInfos = Organization::getOrgName($orgId);
            }
            $view->with('orgInfos', $orgInfos);
        });

        View::composer('layouts.foodwaste', function ($view) {
            $orgInfos = [];
            if (Auth::check()) {
                $user = Auth::user();
                $orgId = getAuthOrgId() ?? $user->org_id_fk;
                $orgInfos = Organization::getOrgName($orgId);
            }
            $view->with('orgInfos', $orgInfos);
        });

        Paginator::useBootstrapFive();
    }
}
