<?php

namespace App\Models\Tabwater;

use App\Models\Admin\Organization;
use App\Models\Admin\Subzone;
use App\Models\Admin\Zone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToOrganization;

class TwMeterInfos extends Model
{
    use HasFactory;
    use BelongsToOrganization;

    public $primaryKey = 'meter_id';
    protected $fillable = [
        "meter_id",
        "org_id_fk",
        "meter_address",
        'submeter_name',
        "user_id",
        "metertype_id",
        "undertake_zone_id",
        "undertake_subzone_id",
        "acceptance_date",
        "status",
        "payment_id",
        "recorder_id",
        'cutmeter',
        'factory_no',
        'inv_no_index',
        'last_meter_recording'
    ];
    protected $table = "tw_meter_infos";
    protected $appends = ['meternumber'];

    /**
     * สร้างรหัสมิเตอร์อัตโนมัติในรูปแบบ TW-001-0001 (TW-org_id_fk-meter_id)
     */
    public function getMeternumberAttribute($value = null): string
    {
        $orgId = $this->attributes['org_id_fk'] ?? (function_exists('getAuthOrgId') ? getAuthOrgId() : 1);
        $meterId = $this->attributes['meter_id'] ?? $this->attributes['id'] ?? 0;
        return sprintf('TW-%03d-%04d', (int) $orgId, (int) $meterId);
    }

    /**
     * รองรับการเรียก $meter->id ให้คืนค่า meter_id เสมอ
     */
    public function getIdAttribute($value = null)
    {
        return $value ?? ($this->attributes['meter_id'] ?? null);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function meter_type()
    {
        return $this->belongsTo(TwMeterType::class, 'metertype_id', 'id');
    }

    public function undertake_zone()
    {
        return $this->belongsTo(Zone::class, 'undertake_zone_id');
    }

    public function undertake_subzone()
    {
        return $this->belongsTo(Subzone::class, 'undertake_subzone_id', 'id');
    }

    public function cutmeter()
    {
        return $this->hasMany(TwCutmeter::class, 'meter_id_fk', 'meter_id');
    }

    public function tw_invoices()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice_temp()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice_currrent_inv_period()
    {
        return $this->hasOne(TwInvoice::class, 'meter_id_fk', 'meter_id')
                ->where('status', 'init')
                ->latestOfMany();
    }

    public function tw_invoice_history()
    {
        return $this->hasMany(TwInvoiceHistory::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice_not_paid()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice_last_inctive_inv_period()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function invoice_by_user_id()
    {
        return $this->hasMany(TwInvoice::class, 'meter_id_fk', 'meter_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id_fk');
    }
}
