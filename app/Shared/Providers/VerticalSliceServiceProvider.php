<?php

namespace App\Shared\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class VerticalSliceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $featuresPath = app_path('Features');
        if (!File::isDirectory($featuresPath)) {
            return;
        }

        $this->registerFeatureRoutesAndViews($featuresPath);
    }

    /**
     * Auto-discover routes and views across all vertical slices.
     */
    protected function registerFeatureRoutesAndViews(string $featuresPath): void
    {
        $contextDirs = File::directories($featuresPath);

        foreach ($contextDirs as $contextDir) {
            $contextName = Str::snake(basename($contextDir));

            // Check if context has top-level views
            $contextViews = $contextDir . '/views';
            if (File::isDirectory($contextViews)) {
                View::addNamespace($contextName, $contextViews);
            }

            // Slices inside context
            $sliceDirs = File::directories($contextDir);
            foreach ($sliceDirs as $sliceDir) {
                // Register Slice Routes
                $routeFile = $sliceDir . '/routes.php';
                if (File::exists($routeFile)) {
                    $sliceName = Str::kebab(basename($sliceDir));
                    
                    Route::middleware(['web'])
                        ->name($contextName . '.' . $sliceName . '.')
                        ->group($routeFile);
                }

                // Register Slice Views
                $sliceViews = $sliceDir . '/views';
                if (File::isDirectory($sliceViews)) {
                    $namespace = $contextName . '-' . Str::kebab(basename($sliceDir));
                    View::addNamespace($namespace, $sliceViews);
                }
            }
        }
    }
}
