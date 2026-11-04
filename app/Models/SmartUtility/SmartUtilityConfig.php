<?php

namespace App\Models\SmartUtility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUtilityConfig extends Model
{
    use HasFactory;

    protected $table = 'smart_utility_configs';

    protected $fillable = [
        'org_id_fk',
        'org_name',
        'area_name',
        'elec_prefix',
        'water_prefix',
        'road_prefix',
        'bldg_prefix',
        'center_lat',
        'center_lng',
        'staff_pass',
        'extra',
    ];

    protected $casts = [
        'center_lat' => 'float',
        'center_lng' => 'float',
        'extra' => 'array',
    ];
}

