<?php

namespace App\Models\AnnualTrash;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualTrashCollectionLog extends Model
{
    use HasFactory;

    protected $table = 'annual_trash_collection_logs';

    protected $fillable = [
        'org_id_fk',
        'annual_trash_id',
        'staff_id',
        'outcome',
        'notes',
        'photo_path',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function bin()
    {
        return $this->belongsTo(AnnualTrash::class, 'annual_trash_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}