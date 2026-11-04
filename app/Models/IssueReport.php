<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'batch_id',
        'issue_type',
        'description',
        'status',
        'staff_comment'
    ];

    // ความสัมพันธ์เชื่อมกลับไปหา User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // ความสัมพันธ์เชื่อมไปหา Batch
    public function batch() {
        return $this->belongsTo(CompostBatches::class, 'batch_id');
    }
}
