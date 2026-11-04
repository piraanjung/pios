<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToOrganization
{
    /**
     * Boot the BelongsToOrganization trait for a model.
     * Laravel จะเรียกฟังก์ชันนี้อัตโนมัติ เพราะชื่อมันตรงกับ boot[TraitName]
     *
     * @return void
     */
    public static function bootBelongsToOrganization()
    {
        // 1. Global Scope: Auto Filter (เติม where table.org_id_fk อัตโนมัติ)
        static::addGlobalScope('org', function (Builder $builder) {
            $orgId = session('active_org_id') ?? (Auth::check() ? Auth::user()->org_id_fk : null);
            if ($orgId) {
                $builder->where($builder->qualifyColumn('org_id_fk'), $orgId);
            }
        });

        // 2. Creating Event: Auto Fill (เติม org_id_fk ตอน create อัตโนมัติ)
        static::creating(function (Model $model) {
            $orgId = session('active_org_id') ?? (Auth::check() ? Auth::user()->org_id_fk : null);
            if ($orgId && empty($model->org_id_fk)) {
                $model->org_id_fk = $orgId;
            }
        });
    }
}
