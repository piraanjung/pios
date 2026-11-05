<?php

namespace Tests\Feature\Ui;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class SoftUiDesignSystemComponentTest extends TestCase
{
    public function test_card_component_renders_structure_and_header(): void
    {
        $rendered = Blade::render(
            '<x-soft-ui.card title="ข้อมูลทั่วไป" subtitle="รายละเอียดสมาชิก" class="custom-card">
                <p>เนื้อหาภายในการ์ด</p>
                <x-slot:footer>
                    <span>ปุ่มส่วนท้าย</span>
                </x-slot:footer>
            </x-soft-ui.card>'
        );

        $this->assertStringContainsString('card shadow-blur custom-card', $rendered);
        $this->assertStringContainsString('ข้อมูลทั่วไป', $rendered);
        $this->assertStringContainsString('รายละเอียดสมาชิก', $rendered);
        $this->assertStringContainsString('เนื้อหาภายในการ์ด', $rendered);
        $this->assertStringContainsString('card-footer', $rendered);
        $this->assertStringContainsString('ปุ่มส่วนท้าย', $rendered);
    }

    public function test_stat_card_renders_gradient_icon_and_percentage(): void
    {
        $rendered = Blade::render(
            '<x-soft-ui.stat-card
                title="จำนวนขวดรีไซเคิล"
                value="12,450"
                percentage="+18.5%"
                percentageColor="text-success"
                icon="ni ni-box-2"
                gradient="primary"
            />'
        );

        $this->assertStringContainsString('จำนวนขวดรีไซเคิล', $rendered);
        $this->assertStringContainsString('12,450', $rendered);
        $this->assertStringContainsString('+18.5%', $rendered);
        $this->assertStringContainsString('text-success', $rendered);
        $this->assertStringContainsString('bg-gradient-primary', $rendered);
        $this->assertStringContainsString('ni ni-box-2', $rendered);
    }

    public function test_button_component_supports_gradient_and_outline(): void
    {
        $btnGradient = Blade::render('<x-soft-ui.button color="success" size="sm">บันทึก</x-soft-ui.button>');
        $this->assertStringContainsString('bg-gradient-success', $btnGradient);
        $this->assertStringContainsString('btn-sm', $btnGradient);
        $this->assertStringContainsString('บันทึก', $btnGradient);

        $btnOutline = Blade::render('<x-soft-ui.button color="danger" :outline="true">ยกเลิก</x-soft-ui.button>');
        $this->assertStringContainsString('btn-outline-danger', $btnOutline);
        $this->assertStringContainsString('ยกเลิก', $btnOutline);
    }

    public function test_badge_component_renders_badge_sm_and_gradient(): void
    {
        $rendered = Blade::render('<x-soft-ui.badge color="info">กำลังดำเนินการ</x-soft-ui.badge>');

        $this->assertStringContainsString('badge badge-sm bg-gradient-info', $rendered);
        $this->assertStringContainsString('กำลังดำเนินการ', $rendered);
    }

    public function test_alert_component_renders_dismissible_button(): void
    {
        $rendered = Blade::render('<x-soft-ui.alert color="warning" :dismissible="true">กรุณาตรวจสอบข้อมูล</x-soft-ui.alert>');

        $this->assertStringContainsString('alert alert-warning', $rendered);
        $this->assertStringContainsString('data-bs-dismiss="alert"', $rendered);
        $this->assertStringContainsString('กรุณาตรวจสอบข้อมูล', $rendered);
    }

    public function test_modal_component_renders_modal_dialog_and_title(): void
    {
        $rendered = Blade::render(
            '<x-soft-ui.modal id="confirmModal" title="ยืนยันการทำรายการ" size="lg">
                <p>คุณต้องการบันทึกข้อมูลหรือไม่?</p>
            </x-soft-ui.modal>'
        );

        $this->assertStringContainsString('id="confirmModal"', $rendered);
        $this->assertStringContainsString('modal-lg', $rendered);
        $this->assertStringContainsString('ยืนยันการทำรายการ', $rendered);
        $this->assertStringContainsString('คุณต้องการบันทึกข้อมูลหรือไม่?', $rendered);
    }

    public function test_datatable_component_renders_table_headers_and_body(): void
    {
        $headers = ['รหัสสมาชิก', 'ชื่อ-สกุล', 'ยอดเงินคงเหลือ', 'การจัดการ'];
        $rendered = Blade::render(
            '<x-soft-ui.datatable id="memberTable" :headers="$headers">
                <tr><td>MBR-001</td><td>สมคิด</td><td>100.00</td><td><button>ดู</button></td></tr>
            </x-soft-ui.datatable>',
            ['headers' => $headers]
        );

        $this->assertStringContainsString('id="memberTable"', $rendered);
        $this->assertStringContainsString('รหัสสมาชิก', $rendered);
        $this->assertStringContainsString('ชื่อ-สกุล', $rendered);
        $this->assertStringContainsString('ยอดเงินคงเหลือ', $rendered);
        $this->assertStringContainsString('MBR-001', $rendered);
        $this->assertStringContainsString('สมคิด', $rendered);
    }
}
