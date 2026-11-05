<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\View;
use Tests\TestCase;

class SoftUiComponentsTest extends TestCase
{
    public function test_soft_ui_card_component_renders(): void
    {
        $view = $this->blade('<x-soft-ui.card title="สถิติประจำวัน">เนื้อหาทดสอบ</x-soft-ui.card>');

        $this->assertStringContainsString('สถิติประจำวัน', $view);
        $this->assertStringContainsString('เนื้อหาทดสอบ', $view);
    }

    public function test_soft_ui_stat_card_component_renders(): void
    {
        $view = $this->blade('<x-soft-ui.stat-card title="ยอดเงินรวม" value="50,000" percentage="+12%" />');

        $this->assertStringContainsString('ยอดเงินรวม', $view);
        $this->assertStringContainsString('50,000', $view);
        $this->assertStringContainsString('+12%', $view);
    }

    public function test_soft_ui_button_component_renders(): void
    {
        $view = $this->blade('<x-soft-ui.button color="success">บันทึกข้อมูล</x-soft-ui.button>');

        $this->assertStringContainsString('bg-gradient-success', $view);
        $this->assertStringContainsString('บันทึกข้อมูล', $view);
    }

    public function test_soft_ui_pro_layout_exists(): void
    {
        $this->assertTrue(View::exists('layouts.soft-ui-pro'));
    }
}
