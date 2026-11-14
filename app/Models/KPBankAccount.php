<?php

namespace App\Models;

use App\Shared\Tenancy\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;

class KPBankAccount extends Model
{
    use BelongsToOrganization;

    protected $table = 'kp_bank_accounts';

protected $fillable = [
    'user_id', 'user_pref_id', 'org_id_fk', 'account_no', 'member_number', 'balance', 'points', 'status', 'entity_type'
];

protected $casts = [
    'balance' => 'decimal:2',
    'points'  => 'integer',
];

public function user()
{
    return $this->belongsTo(User::class);
}
}
