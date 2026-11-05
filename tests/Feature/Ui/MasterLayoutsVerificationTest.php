<?php

namespace Tests\Feature\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class MasterLayoutsVerificationTest extends TestCase
{
    public function test_soft_ui_pro_master_layout_exists_and_has_required_assets(): void
    {
        $this->assertTrue(View::exists('layouts.soft-ui-pro'));

        $content = file_get_contents(resource_path('views/layouts/soft-ui-pro.blade.php'));

        $this->assertStringContainsString('soft-ui-dashboard.css', $content);
        $this->assertStringContainsString('nucleo-icons.css', $content);
        $this->assertStringContainsString("layouts.partials.head-fonts", $content);
        $this->assertStringContainsString("'Kanit'", $content);
        $this->assertStringContainsString('navbarBlur', $content);
        $this->assertStringContainsString('tenant-badge', $content);
    }

    public function test_head_fonts_partial_loads_kanit_with_font_display_swap(): void
    {
        $this->assertTrue(View::exists('layouts.partials.head-fonts'));

        $rendered = View::make('layouts.partials.head-fonts')->render();

        $this->assertStringContainsString('family=Kanit', $rendered);
        $this->assertStringContainsString('display=swap', $rendered);
        $this->assertStringContainsString('preconnect', $rendered);
    }

    public function test_all_standardized_layouts_reference_kanit_or_head_fonts_partial(): void
    {
        $layouts = [
            'views/layouts/soft-ui-pro.blade.php',
            'views/layouts/admin1.blade.php',
            'views/layouts/super-admin.blade.php',
            'views/layouts/welfare.blade.php',
            'views/layouts/keptkaya.blade.php',
            'views/layouts/foodwaste.blade.php',
            'views/layouts/tabwater_staff_mobile.blade.php',
            'views/layouts/user_mobile.blade.php',
            'views/layouts/login.blade.php',
            'views/kiosk/index1.blade.php',
            'views/lineliff/index.blade.php',
        ];

        foreach ($layouts as $relPath) {
            $path = resource_path($relPath);
            $this->assertFileExists($path, "File {$relPath} must exist");
            $content = file_get_contents($path);
            $this->assertTrue(
                str_contains($content, 'layouts.partials.head-fonts') || str_contains($content, 'Kanit'),
                "Layout {$relPath} must reference head-fonts partial or Kanit"
            );
        }
    }

    public function test_flash_partial_renders_alerts_from_session(): void
    {
        Session::flash('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        Session::flash('error', 'เกิดข้อผิดพลาดในการประมวลผล');

        $rendered = View::make('layouts.partials.flash')->render();

        $this->assertStringContainsString('alert-success', $rendered);
        $this->assertStringContainsString('บันทึกข้อมูลเรียบร้อยแล้ว', $rendered);
        $this->assertStringContainsString('alert-danger', $rendered);
        $this->assertStringContainsString('เกิดข้อผิดพลาดในการประมวลผล', $rendered);
    }
}
