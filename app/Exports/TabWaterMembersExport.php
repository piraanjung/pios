<?php 

namespace App\Exports; 

use App\Models\User; 
use Illuminate\Http\Request; 
use Maatwebsite\Excel\Concerns\FromQuery; 
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 
use PhpOffice\PhpSpreadsheet\Style\Fill; 

class TabWaterMembersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles 
{ 
    protected $request; 

    public function __construct(Request $request = null) 
    { 
        $this->request = $request ?? request(); 
    } 

    public function query() 
    { 
        // โหลด Eager Loading ความสัมพันธ์เพิ่มเติมเพื่อลด N+1 Query Error
        $query = User::query()->with([
            'user_zone', 
            'user_subzone', 
            'usermeterinfos.meter_type',
            'usermeterinfos.undertake_zone',
            'usermeterinfos.undertake_subzone'
        ]); 

        if (!empty($this->request->search)) { 
            $search = $this->request->search; 
            $query->where(function ($q) use ($search) { 
                $q->where('firstname', 'like', "%{$search}%") 
                  ->orWhere('lastname', 'like', "%{$search}%") 
                  ->orWhere('phone', 'like', "%{$search}%") 
                  ->orWhereHas('usermeterinfos', function ($m) use ($search) { 
                      $m->where('meter_id', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT('TW-', LPAD(COALESCE(org_id_fk, 1), 3, '0'), '-', LPAD(meter_id, 4, '0')) LIKE ?", ["%{$search}%"]); 
                  }); 
            }); 
        } 

        if (!empty($this->request->undertake_zone_id)) { 
            $zoneId = $this->request->undertake_zone_id; 
            $query->whereHas('usermeterinfos', function ($m) use ($zoneId) { 
                $m->where('undertake_zone_id', $zoneId); 
            }); 
        } 

        if (!empty($this->request->undertake_subzone_id)) { 
            $subzoneId = $this->request->undertake_subzone_id; 
            $query->whereHas('usermeterinfos', function ($m) use ($subzoneId) { 
                $m->where('undertake_subzone_id', $subzoneId); 
            }); 
        } 

        return $query; 
    } 

    public function headings(): array 
    { 
        return [ 
            'สถานะ',
            'ชื่อ - นามสกุล', 
            'เลขที่มิเตอร์', 
            'ที่อยู่', 
            'หมู่ (Zone)', 
            'เส้นจดมิเตอร์', 
            'ที่อยู่ที่ตั้งมิเตอร์', 
            'หมู่ (u_zone)', 
            'เส้นจด(u_subzone)', 
        ]; 
    } 

    public function map($user): array 
    { 
        // 1. ดึงรายการเลขที่มิเตอร์ (รูปแบบ TW-001-0001)
        $meters = $user->usermeterinfos->map(function ($m) { 
            return $m->meternumber; 
        })->filter()->implode(', '); 

        // 2. ดึงที่อยู่ที่ตั้งมิเตอร์
        $meterAddresses = $user->usermeterinfos->map(function ($m) {
            return $m->meter_address ?? '-';
        })->filter()->implode(', ');

        // 3. ดึงหมู่ (undertake_zone) ของมิเตอร์
        $undertakeZones = $user->usermeterinfos->map(function ($m) {
            return $m->undertake_zone->zone_name ?? '-';
        })->filter()->unique()->implode(', ');

        // 4. ดึงเส้นจด (undertake_subzone) ของมิเตอร์
        $undertakeSubzones = $user->usermeterinfos->map(function ($m) {
            return $m->undertake_subzone->subzone_name ?? $m->undertake_subzone->name ?? '-';
        })->filter()->unique()->implode(', ');

        // 5. ดึงสถานะของมิเตอร์
        $statuses = $user->usermeterinfos->map(function ($m) {
            return $m->status ?? '-';
        })->filter()->implode(', ');

        return [ 
             $statuses ?: '-', 
            $user->prefix . $user->firstname . ' ' . $user->lastname, 
            $meters ?: 'ไม่มีรายการมิเตอร์', 
            $user->address ?? '-', 
            $user->user_zone->zone_name ?? '-', 
            $user->user_subzone->subzone_name ?? $user->user_subzone->name ?? '-', 
            $meterAddresses ?: '-', 
            $undertakeZones ?: '-', 
            $undertakeSubzones ?: '-', 
           
        ]; 
    } 

    public function styles(Worksheet $sheet) 
    { 
        // ปรับเป็น A1:I1 ให้ครอบคลุมทั้ง 9 คอลัมน์
        $sheet->getStyle('A1:I1')->applyFromArray([ 
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 
            'fill' => [ 
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '0D6EFD'], 
            ], 
        ]); 

        return []; 
    } 
}