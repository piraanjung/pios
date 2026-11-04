<?php

namespace App\Models;

use App\Models\Admin\District;
use App\Models\Admin\Organization;
use App\Models\Admin\Province;
use App\Models\Admin\Staff;
use App\Models\Admin\Subzone;
use App\Models\Admin\Tambon;
use App\Models\Admin\Zone;
use App\Models\AnnualTrash\AnnualTrashPayment;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\FoodWaste\CompostBatches;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\AnnualTrash\AnnualTrash;
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\UndertakerSubzone;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\FoodWaste\FoodAnnualTrash;
use App\Models\FoodWaste\FoodWasteAccount;
use App\Models\FoodWaste\FoodWasteLog;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\Keptkaya\KpRecycleBankMember;
use App\Models\Tabwater\TwNotifies;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- สำคัญ: ตรวจสอบว่ามีการ use นี้หรือไม่

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        "id",
        "org_id_fk",
        "username",
        "prefix",
        "firstname",
        "lastname",
        "email",
        "password",
        "id_card",
        "line_id",
        'line_user_id',
        "image",
        "phone",
        'age',
        'weight',
        'height',
        "gender",
        "address",
        "zone_id",
        "subzone_id",
        "tambon_code",
        "district_code",
        "province_code",
        "status",
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $table = 'users';

    protected static function booted()
    {
        static::creating(function (User $user) {
            $orgId = session('active_org_id') ?? (auth()->check() ? auth()->user()->org_id_fk : null);
            if ($orgId && empty($user->org_id_fk)) {
                $user->org_id_fk = $orgId;
            }

            // หากสร้าง User ใหม่ขณะอยู่ใน Tenant DB ให้จอง ID จาก Central DB (envsogo_main) ก่อนเพื่อไม่ให้ ID ชนกันข้ามองค์กรและใช้ Login ได้ทันที
            if (config('database.default') === 'tenant' && empty($user->id)) {
                $attributes = $user->getAttributes();
                $now = now();
                $attributes['created_at'] = $attributes['created_at'] ?? $now;
                $attributes['updated_at'] = $attributes['updated_at'] ?? $now;

                $centralId = \Illuminate\Support\Facades\DB::connection('envsogo_main')
                    ->table('users')
                    ->insertGetId($attributes);

                $user->id = $centralId;
            }
        });

        static::updated(function (User $user) {
            if (config('database.default') === 'tenant' && $user->id) {
                $attributes = $user->getAttributes();
                unset($attributes['id']);
                $attributes['updated_at'] = now();

                \Illuminate\Support\Facades\DB::connection('envsogo_main')
                    ->table('users')
                    ->where('id', $user->id)
                    ->update($attributes);
            }
        });

        static::deleted(function (User $user) {
            if (config('database.default') === 'tenant' && $user->id) {
                $query = \Illuminate\Support\Facades\DB::connection('envsogo_main')
                    ->table('users')
                    ->where('id', $user->id);

                if ($user->org_id_fk) {
                    $query->where('org_id_fk', $user->org_id_fk);
                }

                $query->delete();
            }
        });
    }


    public function kpRecycleBankMember()
{
    return $this->hasOne(KpRecycleBankMember::class, 'user_id');
}

public function annualTrashMember()
{
    return $this->hasOne(AnnualTrashMember::class, 'user_id');
}
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id_fk', 'id');
    }

    public function user_zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }

    public function user_subzone()
    {
        return $this->belongsTo(Subzone::class, 'subzone_id', 'id');
    }

    public function undertaker_subzone()
    {
        return $this->hasMany(UndertakerSubzone::class, 'twman_id');
    }

    public function usermeterinfos()
    {
        return $this->hasMany(TwMeterInfos::class, 'user_id', 'id');
    }
    public function user_province()
    {
        return $this->belongsTo(Province::class, 'province_code');
    }

    public function user_district()
    {
        return $this->belongsTo(District::class, 'district_code');
    }

    public function user_tambon()
    {
        return $this->belongsTo(Tambon::class, 'tambon_code');
    }


    public function kpUserPreference()
    {
        return $this->hasOne(KpUserWastePreference::class, 'user_id', 'id');
    }

    public function AnnualTrashs()
    {
        return $this->hasMany(AnnualTrash::class, 'user_id');
    }

    public function getUserAnnualBin()
    {
        return $this->hasOne(AnnualTrash::class, 'id');
    }


    public function annualCollectionPayments()
    {
        return $this->hasMany(AnnualTrashPayment::class);
    }
    public function staffs()
    {
        // User 1 คน มีสิทธิ์ Staff ได้หลายองค์กร (ผูกผ่าน user_id)
        return $this->hasMany(Staff::class, 'user_id', 'id');
    }

    public function foodwastePreference()
    {
        return $this->hasOne(FoodWasteUserPreference::class);
    }

    public function foodAnnualTrashs()
    {
        return $this->hasMany(FoodAnnualTrash::class, 'u_pref_id_fk');
    }

    public function acceptedNotifies(): BelongsToMany // <--- ตรวจสอบการประกาศ Type Hint
    {
        return $this->belongsToMany(TwNotifies::class, 'tw_notify_staff', 'user_id', 'notify_id')
            ->withPivot('staff_status')
            ->withTimestamps();
    }

    // เชื่อมไปหาลอตขยะ
    public function compostBatches()
    {
        return $this->hasMany(CompostBatches::class, 'user_id');
    }

    // เชื่อมไปหาประวัติทิ้งขยะ
    public function wasteLogs()
    {
        return $this->hasMany(FoodWasteLog::class, 'user_id');
    }

    public function calculateTDEE()
    {
        if (!$this->weight || !$this->height || !$this->age) {
            return 0; // ถ้าข้อมูลไม่ครบ ให้คืนค่า 0
        }

        // สูตร Mifflin-St Jeor
        if ($this->gender === 'male') {
            $bmr = (10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) + 5;
        } else {
            $bmr = (10 * $this->weight) + (6.25 * $this->height) - (5 * $this->age) - 161;
        }

        return $bmr * 1.2; // คูณค่ากิจกรรมระดับเริ่มต้น
    }

    // เชื่อมกับบัญชีธนาคารขยะรีไซเคิล (1-to-1)
    /**
     * บัญชีธนาคารขยะของผู้ใช้ (users -> kp_user_waste_preferences -> kp_bank_accounts)
     * RecycleBankController และ view recycle_bank เรียกใช้ชื่อนี้
     */


    public function kpBankAccount()
    {
        return $this->hasMany(KpBankAccount::class, 'user_id', 'id');
    }
    // บัญชีธนาคารขยะรีไซเคิล
    public function recycleBankAccount()
    {
        return $this->hasOne(KpBankAccount::class, 'user_id', 'id')->where('entity_type', 'recycle_bank');
    }

    // บัญชีธนาคารขยะเปียก/เศษอาหาร
    public function foodWasteBankAccount()
    {
        return $this->hasOne(KpBankAccount::class, 'user_id', 'id')->where('entity_type', 'foodwaste_bank');
    }


    // เชื่อมกับบัญชีขยะเปียก (1-to-1)
    public function foodWasteAccount()
    {
        return $this->hasOne(FoodWasteAccount::class, 'user_id', 'id');
    }

    // เชื่อมกับสิทธิ์ขยะรายปี (1-to-1)
    public function annualTrashSubscription()
    {
        return $this->hasOne(AnnualTrashSubscription::class, 'user_id', 'id');
    }

    // เชื่อมกับประวัติการขายขยะ (1-to-Many)
    public function recycleTransactions()
    {
        return $this->hasMany(RecycleTransaction::class, 'user_id', 'id');
    }

    public function assignedNotifies()
    {
        return $this->belongsToMany(
            TwNotifies::class,
            'tw_notify_staff',
            'user_id',          // FK ใน pivot table ที่ชี้มาหา users.id
            'notify_id'         // FK ใน pivot table ที่ชี้ไปหา tw_notifies.id
        )
            ->withPivot('staff_status')
            ->withTimestamps();
    }

    /**
 * ผูกมิเตอร์น้ำประปาใหม่เข้ากับผู้ใช้งาน (Master User)
 */


    public function purchaseTransactions()
    {
        return $this->hasMany(KpPurchaseTransaction::class, 'user_id');
    }

    /**
     * Alias สำหรับ organization() เพื่อรองรับโค้ดที่เรียก $user->org
     */
    public function org()
    {
        return $this->organization();
    }

    /**
     * Alias สำหรับ kpUserPreference() เพื่อรองรับโค้ดที่เรียก $user->wastePreference()
     */
    public function wastePreference()
    {
        return $this->kpUserPreference();
    }

    /**
     * เชื่อมกับตารางสมาชิกสวัสดิการชุมชน (welfare_members)
     */
    public function welfareMember()
    {
        return $this->hasOne(\App\Models\WelfareMember::class, 'user_id', 'id');
    }

    /**
     * Scope กรองเฉพาะผู้ใช้ในองค์กรปัจจุบัน (Multi-Org Isolation)
     */
    public function scopeForOrg($query, $orgId = null)
    {
        $resolvedOrgId = $orgId
            ?? (function_exists('getAuthOrgId') ? getAuthOrgId() : null)
            ?? session('active_org_id')
            ?? (auth()->check() ? auth()->user()->org_id_fk : null);

        if ($resolvedOrgId) {
            return $query->where('users.org_id_fk', $resolvedOrgId);
        }

        return $query;
    }

    /**
     * ดึงยอดเงินคงเหลือจากกระเป๋าเงินธนาคารขยะรีไซเคิล (สำหรับระบบสวัสดิการตัดเงินอัตโนมัติ)
     */
    public function getWalletBalanceAttribute(): float
    {
        if ($this->relationLoaded('recycleBankAccount') && $this->recycleBankAccount) {
            return (float) $this->recycleBankAccount->balance;
        }
        if ($this->relationLoaded('kpRecycleBankMember') && $this->kpRecycleBankMember) {
            return (float) $this->kpRecycleBankMember->balance;
        }

        $bankAcc = $this->recycleBankAccount()->first();
        if ($bankAcc) {
            return (float) $bankAcc->balance;
        }

        $memberAcc = $this->kpRecycleBankMember()->first();
        return $memberAcc ? (float) $memberAcc->balance : 0.0;
    }
}
