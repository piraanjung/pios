<?php

namespace App\Models\SmartUtility;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmartUtilityTicket extends Model
{
    use HasFactory;

    protected $table = 'smart_utility_tickets';

    protected $fillable = [
        'org_id_fk',
        'module',
        'ticket_code',
        'ref_code',
        'village',
        'location',
        'detail',
        'lat',
        'lng',
        'status',
        'cost',
        'photos',
        'messages',
        'meta',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
        'cost' => 'float',
        'photos' => 'array',
        'messages' => 'array',
        'meta' => 'array',
    ];
}

