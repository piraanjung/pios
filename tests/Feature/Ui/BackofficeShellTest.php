<?php

namespace Tests\Feature\Ui;

use Illuminate\Support\Facades\View;
use Tests\TestCase;

class BackofficeShellTest extends TestCase
{
    public function test_shell_has_accessible_navigation_elements(): void
    {
        $content = file_get_contents(resource_path('views/layouts/soft-ui-pro.blade.php'));

        $this->assertStringContainsString('id="navbarBlur"', $content);
        $this->assertStringContainsString('aria-label="breadcrumb"', $content);
        $this->assertStringContainsString('aria-label="เปิดปิดเมนูนำทาง"', $content);
        $this->assertStringContainsString('aria-label="ปิดแถบเมนูนำทาง"', $content);
    }

    public function test_tenant_badge_renders_in_shell(): void
    {
        $content = file_get_contents(resource_path('views/layouts/soft-ui-pro.blade.php'));
        $this->assertStringContainsString('class="tenant-badge"', $content);
        $this->assertStringContainsString('$orgName', $content);
    }
}
