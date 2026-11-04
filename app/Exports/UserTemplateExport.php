<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserTemplateExport implements WithHeadings, ShouldAutoSize, WithStyles, WithTitle, WithColumnWidths, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            "username",      // ชื่อผู้ใช้งาน
            "password",      // รหัสผ่าน
            "prefix",        // คำนำหน้า
            "firstname",     // ชื่อจริง
            "lastname",      // นามสกุล
            "email",         // อีเมล
            "id_card",       // เลขบัตรประชาชน
            "line_id",       // LINE ID (ตัวหนังสือ)
            "line_user_id",  // LINE User ID (UID จากระบบ)
            "phone",         // เบอร์โทรศัพท์
            "age",           // อายุ
            "weight",        // น้ำหนัก
            "height",        // ส่วนสูง
            "gender",        // เพศ (male/female)
            "address",       // ที่อยู่/เลขที่บ้าน
            "zone_id",       // รหัสโซน
            "subzone_id",    // รหัสซอย/ชุมชนย่อย
            "tambon_code",   // รหัสตำบล
            "district_code", // รหัสอำเภอ
            "province_code", // รหัสจังหวัด
            "status",        // สถานะ (active/inactive)
            "is_annual_collection", // เปิดบริการเก็บขยะรายปี (1/0)
            "is_recycle_bank", // เปิดบริการธนาคารขยะ (1/0)
        ];
    }

    public function title(): string
    {
        return 'Users';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 18,
            'C' => 12,
            'D' => 22,
            'E' => 22,
            'F' => 30,
            'G' => 18,
            'H' => 20,
            'I' => 24,
            'J' => 18,
            'K' => 12,
            'L' => 12,
            'M' => 12,
            'N' => 12,
            'O' => 30,
            'P' => 16,
            'Q' => 16,
            'R' => 16,
            'S' => 16,
            'T' => 18,
            'U' => 24,
            'V' => 24,
            'W' => 20,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'J' => NumberFormat::FORMAT_TEXT,
            'P' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
            'R' => NumberFormat::FORMAT_TEXT,
            'S' => NumberFormat::FORMAT_TEXT,
            'T' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // กำหนดให้แถวที่ 1 (หัวตาราง) เป็นตัวหนา
            1 => ['font' => ['bold' => true]],
        ];
    }
}
