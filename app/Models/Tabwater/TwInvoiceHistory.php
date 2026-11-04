<?php

namespace App\Models\Tabwater;

use App\Models\User;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\TwAccTransactions;
use App\Traits\BelongsToOrganization; // <--- เรียกใช้แค่ตัวนี้พอ
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TwInvoiceHistory extends Model
{
    // ใช้แค่ BelongsToOrganization ตัวเดียว (มันสลับ DB ให้แล้ว)
    use HasFactory, BelongsToOrganization;

    protected $table = 'tw_invoice_history';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'inv_period_id_fk',
        'inv_no',
        'meter_id_fk',
        'lastmeter',
        'water_used',
        'reserve_meter',
        'inv_type',
        'paid',
        'vat',
        'totalpaid',
        'acc_trans_id_fk',
        'currentmeter',
        'recorder_id',
        'status',
        'created_at',
        'updated_at',
        'org_id_fk' // ต้องมี column นี้ใน DB ตามที่คุยกัน
    ];

    public function tw_meter_infos()
    {
        return $this->belongsTo(TwMeterInfos::class, 'meter_id_fk', 'meter_id');
    }
    public function invoice_period()
    {
        return $this->belongsTo(InvoicePeriod::class, 'inv_period_id_fk', 'id');
    }

    public function tw_acc_transactions()
    {
        return $this->belongsTo(TwAccTransactions::class, 'acc_trans_id_fk', 'id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorder_id', 'id');
    }
    // ----------------------------------------------------------------------
    // ฟังก์ชัน Generate Invoice No (ปรับปรุงใหม่)
    // ----------------------------------------------------------------------
}
