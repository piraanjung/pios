<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $palette = ['#dc2626', '#f97316', '#eab308', '#8b5cf6', '#0ea5e9', '#ec4899', '#14b8a6', '#6b7280'];

    public function up(): void
    {
        foreach (['foodwaste_issue_types', 'issue_types'] as $tbl) {
            if (Schema::hasTable($tbl) && !Schema::hasColumn($tbl, 'color')) {
                Schema::table($tbl, function (Blueprint $t) {
                    $t->string('color', 9)->default('#dc2626');
                });
                foreach (DB::table($tbl)->orderBy('id')->pluck('id') as $i => $id) {
                    DB::table($tbl)->where('id', $id)->update(['color' => $this->palette[$i % count($this->palette)]]);
                }
            }
        }

        if (Schema::hasTable('foodwaste_bins')) {
            Schema::table('foodwaste_bins', function (Blueprint $t) {
                if (!Schema::hasColumn('foodwaste_bins', 'org_id_fk')) {
                    $t->unsignedBigInteger('org_id_fk')->nullable()->index();
                }
                if (!Schema::hasColumn('foodwaste_bins', 'u_pref_id_fk')) {
                    $t->unsignedBigInteger('u_pref_id_fk')->nullable()->index();
                }
                if (!Schema::hasColumn('foodwaste_bins', 'bin_code_fk')) {
                    $t->unsignedBigInteger('bin_code_fk')->nullable();
                }
                if (!Schema::hasColumn('foodwaste_bins', 'iotbox_id_fk')) {
                    $t->unsignedBigInteger('iotbox_id_fk')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['foodwaste_issue_types', 'issue_types'] as $tbl) {
            if (Schema::hasColumn($tbl, 'color')) {
                Schema::table($tbl, fn (Blueprint $t) => $t->dropColumn('color'));
            }
        }
    }
};
