<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $configTable = Schema::hasTable('kp_welfare_configs') ? 'kp_welfare_configs' : (Schema::hasTable('welfare_configs') ? 'welfare_configs' : null);
        if ($configTable) {
            Schema::table($configTable, function (Blueprint $table) use ($configTable) {
                if (!Schema::hasColumn($configTable, 'workflow_id')) {
                    $table->foreignId('workflow_id')->nullable()->constrained('approval_workflows')->onDelete('set null');
                }
                if (!Schema::hasColumn($configTable, 'contribution_fee_per_case')) {
                    $table->decimal('contribution_fee_per_case', 10, 2)->default(60.00);
                }
            });
        }

        $payoutTable = Schema::hasTable('kp_welfare_payouts') ? 'kp_welfare_payouts' : (Schema::hasTable('welfare_payouts') ? 'welfare_payouts' : null);
        if ($payoutTable) {
            Schema::table($payoutTable, function (Blueprint $table) use ($payoutTable) {
                if (!Schema::hasColumn($payoutTable, 'current_step_id')) {
                    $table->foreignId('current_step_id')->nullable()->constrained('approval_workflow_steps')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('welfare_configs')) {
            Schema::table('welfare_configs', function (Blueprint $table) {
                $table->dropColumn(['workflow_id', 'contribution_fee_per_case']);
            });
        }
        if (Schema::hasTable('welfare_payouts')) {
            Schema::table('welfare_payouts', function (Blueprint $table) {
                $table->dropColumn('current_step_id');
            });
        }
    }
};