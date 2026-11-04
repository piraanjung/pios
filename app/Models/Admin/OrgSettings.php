<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgSettings extends Model
{
    use HasFactory;

    protected $connection = 'envsogo_main';
    protected $table  = 'organizations';
    protected $fillable = [
        'id',
        'org_code',
        'org_type_name',
        'org_short_type_name',
        'org_name',
        'org_address',
        'org_zone_id_fk',
        'org_tambon_id_fk',
        'org_district_id_fk',
        'org_province_id_fk',
        'org_route',
        'org_zipcode',
        'org_phone',
        'org_dept_name',
        'org_dept_short_name',
        'org_dept_phone',
        'org_head_name',
        'fin_head_name',
        'tw_head_name',
        'fin_head_license_img',
        'org_head_license_img',
        'tw_head_license_img',
        'org_logo_img',
        'meternumbercode',
        'org_database',
        'cutmeter_count',
        'vat',
    ];

    protected $casts = [
        'vat' => 'float:2',
    ];

    public function provinces()
    {
        return $this->belongsTo(Province::class, 'org_province_id_fk', 'id');
    }

    public function districts()
    {
        return $this->belongsTo(District::class, 'org_district_id_fk', 'id');
    }

    public function tambons()
    {
        return $this->belongsTo(Tambon::class, 'org_tambon_id_fk', 'id');
    }

    public static function getOrgInfos($org_id_fk)
    {
        if (empty($org_id_fk)) {
            return null;
        }

        $connection = Organization::on('envsogo_main')
            ->where('id', $org_id_fk)
            ->with(['provinces', 'tambons', 'districts', 'orgType'])
            ->first();

        if (!$connection) {
            return null;
        }

        return [
            'id'                  => $org_id_fk,
            'org_database'        => $connection->org_database,
            'org_zipcode'         => $connection->org_zipcode ?? '',
            'org_address'         => $connection->org_address ?? '',
            'org_phone'           => $connection->org_phone ?? '',
            'org_head_name'       => $connection->org_head_name ?? '',
            'org_code'            => $connection->org_code ?? '',
            'org_guard'           => $connection->org_guard ?? '',
            'org_provinces'       => $connection->provinces?->province_name ?? '',
            'org_districts'       => $connection->districts?->district_name ?? '',
            'org_tambons'         => $connection->tambons?->tambon_name ?? '',
            'org_logo_img'        => $connection->org_logo_img ?? '',
            'org_type_name'       => $connection->orgType?->name ?? ($connection->org_type_name ?? ''),
            'org_name'            => $connection->org_name ?? '',
            'org_short_type_name' => $connection->orgType?->code ?? ($connection->org_short_name ?? ''),
            'org_dept_name'       => $connection->org_dept_name ?? '',
            'org_dept_phone'      => $connection->org_dept_phone ?? '',
            'vat'                 => $connection->vat,
        ];
    }
}
