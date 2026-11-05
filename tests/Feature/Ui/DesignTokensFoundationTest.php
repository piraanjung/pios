<?php

namespace Tests\Feature\Ui;

use Illuminate\Support\Facades\View;
use Tests\TestCase;

class DesignTokensFoundationTest extends TestCase
{
    public function test_tokens_css_exists_and_declares_core_variables(): void
    {
        $path = resource_path('css/tokens.css');
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString('--color-primary: var(--navy-base)', $content);
        $this->assertStringContainsString('--font-family-sans: \'Kanit\'', $content);
        $this->assertStringContainsString('--gradient-primary:', $content);
        $this->assertStringContainsString('--gradient-info:', $content);
        $this->assertStringContainsString('--gradient-success:', $content);
        $this->assertStringContainsString('prefers-reduced-motion', $content);
    }

    public function test_public_tokens_css_is_in_sync_with_resources(): void
    {
        $resPath = resource_path('css/tokens.css');
        $pubPath = public_path('css/tokens.css');

        $this->assertFileExists($pubPath);
        $this->assertEquals(
            file_get_contents($resPath),
            file_get_contents($pubPath),
            'public/css/tokens.css must match resource tokens.css'
        );
    }

    public function test_soft_ui_pro_layout_links_tokens_stylesheet(): void
    {
        $content = file_get_contents(resource_path('views/layouts/soft-ui-pro.blade.php'));
        $this->assertStringContainsString('css/tokens.css', $content);
    }
}
