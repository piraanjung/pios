<?php

namespace App\Models\KeptKaya;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\Zone;

class KpRecycleBankMember extends Model
{
    protected $table = 'kp_recycle_bank_members';
    protected $guarded = [];

    // Accessor แปลงเลขรันลำดับ 1, 2, 3... ให้เป็นรหัสฟอร์แมต RB00001
    public function getFormattedMemberNumberAttribute()
    {
        if (!$this->member_number) return '-';
        return 'RB' . sprintf('%05d', $this->member_number);
    }

    // Relation กลับไปหา Master User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}