<?php

namespace App\Models\Tabwater;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgInvoiceSequence extends Model
{
    use HasFactory;

    protected $table = 'org_invoice_sequences';

    protected $fillable = [
        'org_id_fk',
        'year',
        'last_running_no',
    ];
}
