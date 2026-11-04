<?php

namespace App\Models\SmartUtility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUtilityItem extends Model
{
    use HasFactory;

    protected $table = 'smart_utility_items';

    protected $fillable = [
        'org_id_fk',
        'module',
        'item_code',
        'name',
        'item_type',
        'category',
        'village',
        'lat',
        'lng',
        'status',
        'condition',
        'year_be',
        'area_sqm',
        'value_baht',
        'owner',
        'note',
        'meta',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'area_sqm' => 'float',
        'value_baht' => 'float',
        'meta' => 'array',
    ];
}

