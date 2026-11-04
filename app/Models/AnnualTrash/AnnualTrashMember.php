<?php

namespace App\Models\AnnualTrash;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\Zone;
use App\Models\Admin\Subzone;
use App\Models\KeptKaya\KpUserGroup;

class AnnualTrashMember extends Model
{
    use HasFactory;

    protected $table = 'annual_trash_members';
    protected $fillable = 
    [
        'user_id',
        'member_number',
        'zone_id',
        'subzone_id',
        'user_group_id_fk',
        'recorder_id',
        'org_id_fk', // ต้องมี column นี้ใน DB ตามที่คุยกัน
    ];

    protected $guarded = [];

    // Accessor แปลงเลขรันลำดับ 1, 2, 3... ให้เป็นรหัสฟอร์แมต AT00001
    public function getFormattedMemberNumberAttribute()
    {
        if (!$this->member_number) return '-';
        return 'AT' . sprintf('%05d', $this->member_number);
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

    public function subzone()
    {
        return $this->belongsTo(Subzone::class, 'subzone_id');
    }

    public function userGroup()
    {
        return $this->belongsTo(KpUserGroup::class, 'user_group_id_fk');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorder_id');
    }
}