<?php

namespace Tests\Feature;

use Tests\TestCase;

class AccCaseSensitivityTest extends TestCase
{
    public function test_acc_controllers_and_models_have_strict_case_sensitivity(): void
    {
        $keptkayaController = file_get_contents(app_path('Http/Controllers/Api/KeptkayaController.php'));
        $this->assertDoesNotMatchRegularExpression(
            '/\buse\s+App\\\\Models\\\\KpBankAccount\b/',
            $keptkayaController,
            'KeptkayaController must use App\Models\KPBankAccount with exact PSR-4 casing.'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\bKpBankAccount::/',
            $keptkayaController,
            'KeptkayaController must call KPBankAccount:: with exact uppercase KP.'
        );

        $files = [
            app_path('Http/Controllers/AccessMenusController.php'),
            app_path('Http/Controllers/Api/UsersController.php'),
            app_path('Http/Controllers/Api/KeptkayaController.php'),
            app_path('Http/Controllers/Tabwater/UndertakerSubzoneController.php'),
            app_path('Http/Controllers/Tabwater/UserMeterInfosController.php'),
            app_path('Http/Controllers/Tabwater/PaymentController.php'),
        ];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all('/^use\s+(App\\\\[^;\s]+);/m', $content, $matches);
            foreach ($matches[1] as $class) {
                $relative = 'app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
                $parts = explode('/', $relative);
                $cur = base_path();
                foreach ($parts as $p) {
                    $entries = is_dir($cur) ? scandir($cur) : [];
                    $this->assertContains(
                        $p,
                        $entries,
                        "Case sensitivity mismatch in {$file}: {$class} (path {$relative} failed at segment {$p})"
                    );
                    $cur .= '/' . $p;
                }
            }
        }
    }

    public function test_acc_views_and_routes_exist(): void
    {
        $this->assertTrue(file_exists(resource_path('views/accessmenu.blade.php')));
        $this->assertTrue(file_exists(resource_path('views/dashboard.blade.php')));
        $this->assertTrue(file_exists(resource_path('views/staff/mobile/staff_accessmenu.blade.php')));
        $this->assertTrue(file_exists(public_path('js/accessmenu.js')));
        $this->assertTrue(file_exists(public_path('css/accessmenu.css')));
    }
}
