<?php 

namespace App\Models\AnnualTrash; 

use App\Models\AnnualTrash\AnnualTrashSubscription; 
use App\Models\KeptKaya\KpUserGroup; 
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\User; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 

class AnnualTrash extends Model 
{ 
    use HasFactory; 

    protected $table = 'annual_trashs'; 

    protected $fillable = [ 
        'id', 
        'org_id_fk',
        'user_id', 
        'bin_code', 
        'bin_type', 
        'location_description', 
        'latitude', 
        'longitude', 
        'status', 
        'is_active_for_annual_collection', 
    ]; 

    protected $casts = [ 
        'is_active_for_annual_collection' => 'boolean', 
    ]; 

    public function kpUserGroup() 
    { 
        return $this->belongsTo(KpUserGroup::class, 'bin_type', 'id'); 
    } 

    // Master User Relationship
    public function user() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    } 

    // Module Member Relationship
    public function annualTrashMember()
    {
        return $this->belongsTo(AnnualTrashMember::class, 'user_id', 'user_id');
    }

    public function subscriptions() 
    { 
        return $this->hasMany(AnnualTrashSubscription::class, 'waste_bin_id', 'id'); 
    } 

    public function collectionLogs()
    {
        return $this->hasMany(AnnualTrashCollectionLog::class, 'annual_trash_id');
    }
}