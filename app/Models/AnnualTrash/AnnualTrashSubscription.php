<?php 

namespace App\Models\AnnualTrash; 

use App\Models\AnnualTrash\AnnualTrash; 
use App\Models\AnnualTrash\AnnualTrashMember; 
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth;
use App\Models\User; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 

class AnnualTrashSubscription extends Model 
{ 
    use HasFactory; 

    protected $table = 'annual_trash_subscriptions'; 

    protected $fillable = [ 
        'org_id_fk', 
        'fiscal_year', 
        'user_id', 
        'waste_bin_id', 
        'payrate_permonth_id_fk', 
        'annual_fee', 
        'month_fee', 
        'total_paid_amt', 
        'status', 
        'created_at', 
        'updated_at', 
    ]; 

    // Relation ไปยังถังขยะ
    public function AnnualTrash() 
    { 
        return $this->belongsTo(AnnualTrash::class, 'waste_bin_id', 'id'); 
    } 

    // Relation ตรงไปยัง Master User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relation ไปยังตารางอัตราค่าบริการรายเดือน
    public function payratePerMonth()
    {
        return $this->belongsTo(AnnualTrashPayratePerMonth::class, 'payrate_permonth_id_fk', 'id');
    }

    // Relation ไปยังรายการชำระเงิน
    public function payments() 
    { 
        return $this->hasMany(AnnualTrashPayment::class, 'wbs_id', 'id'); 
    } 

    // Relation ไปยังสมาชิกขยะรายปีผ่านถังขยะ
    public function annualTrashMember() 
    { 
        return $this->hasOneThrough( 
            AnnualTrashMember::class, 
            AnnualTrash::class, 
            'id',           // Foreign key on annual_trashs
            'user_id',      // Foreign key on annual_trash_members
            'waste_bin_id', // Local key on annual_trash_subscriptions
            'user_id'       // Local key on annual_trashs
        ); 
    } 

    // ฟังก์ชันคำนวณปีงบประมาณ พ.ศ. ปัจจุบัน
    public static function calculateFiscalYear() 
    { 
        $now = now(); 
        $year = $now->year; 
        if ($now->month >= 10) { 
            $year++; 
        } 
        return $year + 543; 
    } 
}