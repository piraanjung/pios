<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure `welfare_configs` table exists
        if (!Schema::hasTable('welfare_configs')) {
            Schema::create('welfare_configs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->default(1);
                $table->integer('min_months_active')->default(6);
                $table->integer('min_total_weight')->default(50);
                $table->decimal('default_payout_amount', 12, 2)->default(5000.00);
                $table->text('criteria_description')->nullable();
                $table->unsignedBigInteger('workflow_id')->nullable();
                $table->decimal('contribution_fee_per_case', 10, 2)->default(60.00);
                $table->timestamps();
            });

            DB::table('welfare_configs')->insertOrIgnore([
                'id' => 1,
                'org_id_fk' => 1,
                'min_months_active' => 6,
                'min_total_weight' => 50,
                'default_payout_amount' => 5000.00,
                'contribution_fee_per_case' => 60.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Ensure necessary columns exist in welfare_configs
            Schema::table('welfare_configs', function (Blueprint $table) {
                if (!Schema::hasColumn('welfare_configs', 'org_id_fk')) {
                    $table->unsignedBigInteger('org_id_fk')->default(1)->after('id');
                }
                if (!Schema::hasColumn('welfare_configs', 'workflow_id')) {
                    $table->unsignedBigInteger('workflow_id')->nullable();
                }
                if (!Schema::hasColumn('welfare_configs', 'contribution_fee_per_case')) {
                    $table->decimal('contribution_fee_per_case', 10, 2)->default(60.00);
                }
            });
        }

        // 2. Ensure `welfare_payouts` table exists
        if (!Schema::hasTable('welfare_payouts')) {
            Schema::create('welfare_payouts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->default(1);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('deceased_name')->nullable();
                $table->string('beneficiary_name')->nullable();
                $table->decimal('amount', 12, 2)->default(0.00);
                $table->string('death_certificate_img')->nullable();
                $table->text('note')->nullable();
                $table->unsignedBigInteger('requester_id')->nullable();
                $table->unsignedBigInteger('approver_id')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->unsignedBigInteger('current_step_id')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('welfare_payouts', function (Blueprint $table) {
                if (!Schema::hasColumn('welfare_payouts', 'org_id_fk')) {
                    $table->unsignedBigInteger('org_id_fk')->default(1)->after('id');
                }
                if (!Schema::hasColumn('welfare_payouts', 'current_step_id')) {
                    $table->unsignedBigInteger('current_step_id')->nullable();
                }
            });
        }

        // 3. Ensure `kp_money_requests` table has all expected columns
        if (Schema::hasTable('kp_money_requests')) {
            Schema::table('kp_money_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('kp_money_requests', 'org_id_fk')) {
                    $table->unsignedBigInteger('org_id_fk')->default(1);
                }
                if (!Schema::hasColumn('kp_money_requests', 'amount')) {
                    $table->decimal('amount', 12, 2)->default(0.00);
                }
                if (!Schema::hasColumn('kp_money_requests', 'verification_code')) {
                    $table->string('verification_code', 10)->default('');
                }
                if (!Schema::hasColumn('kp_money_requests', 'is_proxy')) {
                    $table->boolean('is_proxy')->default(false);
                }
                if (!Schema::hasColumn('kp_money_requests', 'proxy_name')) {
                    $table->string('proxy_name')->nullable();
                }
                if (!Schema::hasColumn('kp_money_requests', 'payout_date')) {
                    $table->date('payout_date')->nullable();
                }
                if (!Schema::hasColumn('kp_money_requests', 'admin_id')) {
                    $table->unsignedBigInteger('admin_id')->nullable();
                }
                if (!Schema::hasColumn('kp_money_requests', 'completed_at')) {
                    $table->timestamp('completed_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe no-op
    }
};
