<?php

namespace App\Shared\Tenancy;

use App\Models\Organization;
use Illuminate\Support\Facades\Session;

class TenantContext
{
    protected static ?int $orgId = null;

    /**
     * Get current active organization ID.
     */
    public static function currentOrgId(): ?int
    {
        if (static::$orgId !== null) {
            return static::$orgId;
        }

        try {
            if (app()->bound('session') && Session::isStarted() && Session::has('org_id')) {
                return (int) Session::get('org_id');
            }
        } catch (\Throwable $e) {
            // Ignore when session store / facade root is not initialized
        }

        try {
            if (function_exists('auth') && auth()->check() && auth()->user()->org_id_fk) {
                return (int) auth()->user()->org_id_fk;
            }
        } catch (\Throwable $e) {
            // Ignore when auth is not initialized
        }

        return null;
    }

    /**
     * Explicitly set current active organization ID for this request/lifecycle.
     */
    public static function setOrgId(?int $orgId): void
    {
        static::$orgId = $orgId;
        try {
            if (app()->bound('session') && Session::isStarted()) {
                if ($orgId !== null) {
                    Session::put('org_id', $orgId);
                } else {
                    Session::forget('org_id');
                }
            }
        } catch (\Throwable $e) {
            // Ignore when session store / facade root is not initialized
        }
    }

    /**
     * Retrieve the current Organization model.
     */
    public static function currentOrg(): ?Organization
    {
        $id = static::currentOrgId();
        if (!$id) {
            return null;
        }

        return Organization::find($id);
    }

    /**
     * Reset the tenant context (useful in test suites and CLI daemons).
     */
    public static function reset(): void
    {
        static::$orgId = null;
        try {
            if (Session::isStarted() || app()->bound('session')) {
                Session::forget('org_id');
            }
        } catch (\Throwable $e) {
            // Ignore when session store is not initialized
        }
    }
}
