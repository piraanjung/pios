<?php

namespace App\Models\Tabwater;

use App\Models\Admin\BudgetYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Traits\BelongsToOrganization;

class InvoicePeriod extends Model
{
    use HasFactory;
    use BelongsToOrganization;

    protected $fillable = [
        'id',
        'org_id_fk',
        'old_inv_p_id',
        'inv_p_name',
        'inv_p_name_int',
        'budgetyear_id',
        'startdate',
        'enddate',
        'status',
    ];
    protected $table = "invoice_period";

    protected static function booted()
    {
        static::creating(function (self $model) {
            if (Schema::hasColumn($model->getTable(), 'old_inv_p_id') && is_null($model->old_inv_p_id)) {
                $model->old_inv_p_id = 0;
            }
        });
    }

    public function budgetyear()
    {
        return $this->belongsTo(BudgetYear::class, 'budgetyear_id', 'id');
    }

    public static function get_curr_inv_pd()
    {
        return self::where('status', 'active')
            ->latest('id')
            ->first();
    }
}
