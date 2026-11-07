<?php

namespace App\Shared\Tenancy;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope('organization_isolation', function (Builder $builder) {
            $currentOrgId = TenantContext::currentOrgId();
            if ($currentOrgId !== null) {
                $builder->where($builder->getModel()->getTable() . '.org_id_fk', $currentOrgId);
            }
        });

        static::creating(function ($model) {
            if (empty($model->org_id_fk)) {
                $currentOrgId = TenantContext::currentOrgId();
                if ($currentOrgId !== null) {
                    $model->org_id_fk = $currentOrgId;
                }
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'org_id_fk', 'id');
    }

    public function scopeForOrg(Builder $query, int $orgId): Builder
    {
        return $query->withoutGlobalScope('organization_isolation')->where($this->getTable() . '.org_id_fk', $orgId);
    }
}
