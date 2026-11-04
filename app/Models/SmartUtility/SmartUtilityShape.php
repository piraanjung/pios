<?php

namespace App\Models\SmartUtility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUtilityShape extends Model
{
    use HasFactory;

    protected $table = 'smart_utility_shapes';

    protected $fillable = [
        'org_id_fk',
        'module',
        'shape_type',
        'kind',
        'name',
        'coordinates',
        'radius',
        'properties',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'radius' => 'float',
        'properties' => 'array',
    ];
}

