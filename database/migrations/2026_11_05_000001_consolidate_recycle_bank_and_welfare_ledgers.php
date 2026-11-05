<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. เพิ่ม member_number เข้าสู่ kp_bank_accounts เพื่อรวมศูนย์เลขสมาชิก (ตาม ADR-0004)
        if (Schema::hasTable('kp_bank_accounts')) {
            Schema::table('kp_bank_accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('kp_bank_accounts', 'member_number')) {
                    $table->string('member_number', 64)->nullable()->after('id')->index();
                }
            });
        }

        // 2. สร้างตารางสมุดบัญชีเงินกองทุนสวัสดิการ (Welfare Fund Ledgers)
        if (!Schema::hasTable('welfare_fund_ledgers')) {
            Schema::create('welfare_fund_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('payout_id')->nullable()->index();
                $table->decimal('amount', 12, 2)->default(0.00);
                $table->decimal('balance_after', 12, 2)->default(0.00);
                $table->string('type', 32)->index(); // deposit, welfare_deduction, payout, adjustment
                $table->string('reference_type', 64)->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index(['org_id_fk', 'user_id']);
                $table->index(['org_id_fk', 'type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welfare_fund_ledgers');

        if (Schema::hasTable('kp_bank_accounts')) {
            Schema::table('kp_bank_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('kp_bank_accounts', 'member_number')) {
                    $table->dropColumn('member_number');
                }
            });
        }
    }
};
