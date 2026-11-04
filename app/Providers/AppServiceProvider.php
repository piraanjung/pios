<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
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

        if (env(key: 'APP_ENV') === 'local' && request()->server(key: 'HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme(scheme: 'https');
        }
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
