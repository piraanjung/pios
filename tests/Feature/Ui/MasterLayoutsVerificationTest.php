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
        $this->assertStringContainsString('Sarabun', $content);
        $this->assertStringContainsString('navbarBlur', $content);
        $this->assertStringContainsString('tenant-badge', $content);
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
