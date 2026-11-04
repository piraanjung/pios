<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Organization extends Model
{
    use HasFactory; // 👈 เพิ่ม Trait นี้เข้ามา
    protected $connection = 'envsogo_main';
    protected $table  = 'organizations';
    protected $fillable = [
        'org_code',
        'org_type_id',
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
        'lat',
        'lng'
    ];

    protected $casts = [
        'vat' => 'float:2', // แปลง vat เป็น float และแสดง 2 ตำแหน่ง
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

    public function zones()
    {
        return $this->belongsTo(Zone::class, 'org_zone_id_fk', 'id');
    }

    public function orgType(){
        return $this->belongsTo(OrganizationType::class, 'org_type_id', 'id');
    }

    /**
     * Get organization database name.
     *
     * @param int|string|array $org_id_fk
     * @return mixed
     */
    public static function getOrgName($org_id_fk)
    {
        if (empty($org_id_fk)) {
            return null;
        }

        $organization = Organization::on('envsogo_main')->where('id', $org_id_fk);
        $connection = $organization->with(['provinces', 'tambons', 'districts', 'zones', 'orgType'])
            ->get()->first();

        if (!$connection) {
            return null;
        }

        return [
            'id'                    => $org_id_fk,
            'org_database'          => $connection->org_database,
            'org_code'              => $connection->org_code ?? '',
            'org_address'           => $connection->org_address ?? '',
            'org_zipcode'           => $connection->org_zipcode ?? '',
            'org_province_id'       => $connection->provinces?->id,
            'org_province'          => $connection->provinces?->province_name ?? '',
            'org_district_id'       => $connection->districts?->id,
            'org_district'          => $connection->districts?->district_name ?? '',
            'org_tambon_id'         => $connection->tambons?->id,
            'org_tambon'            => $connection->tambons?->tambon_name ?? '',
            'org_zone_id'           => $connection->zones?->id,
            'org_zone'              => $connection->zones?->zone_name ?? '',
            'org_phone'             => $connection->org_phone ?? '',
            'org_logo_img'          => $connection->org_logo_img ?? '',
            'org_type_name'         => $connection->orgType?->name ?? '',
            'org_name'              => $connection->org_name ?? '',
            'org_short_type_name'   => $connection->orgType?->code ?? '',
            'org_dept_name'         => $connection->org_dept_name ?? '',
            'lat'                   => $connection->lat,
            'long'                  => $connection->lng ?? $connection->long ?? null,
            'lng'                   => $connection->lng ?? $connection->long ?? null,
        ];
    }

    /**
     * Get organization database name.
     *
     * @param int|string $org_id_code
     * @return mixed
     */
    public static function getOrgDatabase($org_id_code)
    {
        $organization = (new Organization())->setConnection('envsogo_main')->where('org_code', $org_id_code)
            ->get(['id', 'org_database'])->first();
        return $organization;
    }

    public static function getOrgInfos($org_id_fk)
    {
        return self::getOrgName($org_id_fk);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'org_id_fk');
    }
}
