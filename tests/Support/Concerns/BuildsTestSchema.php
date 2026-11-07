<?php

namespace Tests\Support\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait BuildsTestSchema
{
    /**
     * Build standard in-memory test database schema for VSA and multi-tenant testing.
     */
    protected function buildStandardTestSchema(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        if (!Schema::hasTable('organizations')) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->string('org_code', 50)->nullable();
                $table->string('org_name', 150);
                $table->string('org_short_type_name', 50)->nullable();
                $table->string('org_type_name', 50)->nullable();
                $table->text('org_address')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->string('username', 100)->nullable();
                $table->string('prefix', 30)->nullable();
                $table->string('firstname', 100)->nullable();
                $table->string('lastname', 100)->nullable();
                $table->string('email', 150)->nullable();
                $table->string('password')->nullable();
                $table->string('id_card', 20)->nullable()->index();
                $table->string('phone', 30)->nullable();
                $table->string('line_id', 100)->nullable();
                $table->string('line_user_id', 100)->nullable()->index();
                $table->string('image')->nullable();
                $table->string('gender', 20)->nullable();
                $table->text('address')->nullable();
                $table->unsignedBigInteger('zone_id')->nullable();
                $table->unsignedBigInteger('subzone_id')->nullable();
                $table->string('tambon_code', 20)->nullable();
                $table->string('district_code', 20)->nullable();
                $table->string('province_code', 20)->nullable();
                $table->integer('status')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_user_waste_preferences')) {
            Schema::create('kp_user_waste_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->boolean('is_annual_collection')->default(false);
                $table->boolean('is_waste_bank')->default(false);
                $table->boolean('is_recycle_bank')->default(false);
                $table->text('address')->nullable();
                $table->unsignedBigInteger('zone_id')->nullable();
                $table->unsignedBigInteger('subzone_id')->nullable();
                $table->string('tambon_code', 20)->nullable();
                $table->string('district_code', 20)->nullable();
                $table->string('province_code', 20)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_bank_accounts')) {
            Schema::create('kp_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('user_pref_id')->nullable()->index();
                $table->string('account_no', 64)->nullable()->index();
                $table->string('member_number', 64)->nullable()->index();
                $table->decimal('balance', 12, 2)->default(0.00);
                $table->integer('points')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_purchase_transactions')) {
            Schema::create('kp_purchase_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('kp_u_trans_no', 64)->unique();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('kiosk_id_fk')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('kp_user_w_pref_id_fk')->nullable()->index();
                $table->unsignedBigInteger('machine_id_fk')->nullable();
                $table->dateTime('transaction_date')->nullable();
                $table->decimal('total_weight', 10, 2)->default(0.00);
                $table->decimal('total_amount', 12, 2)->default(0.00);
                $table->integer('total_points')->default(0);
                $table->string('status', 30)->default('completed');
                $table->unsignedBigInteger('recorder_id')->nullable();
                $table->decimal('cash_back', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_purchase_transactions_details')) {
            Schema::create('kp_purchase_transactions_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('kp_purchase_trans_id')->nullable()->index();
                $table->unsignedBigInteger('kp_purchase_transaction_id_fk')->nullable()->index();
                $table->unsignedBigInteger('kp_recycle_item_id')->default(1);
                $table->unsignedBigInteger('kp_units_idfk')->default(2);
                $table->unsignedBigInteger('kp_tbank_items_pricepoint_id')->nullable();
                $table->decimal('amount_in_units', 10, 2)->default(1.00);
                $table->decimal('price_per_unit', 10, 2)->default(0.00);
                $table->decimal('amount', 12, 2)->default(0.00);
                $table->integer('points')->default(0);
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('foodwaste_user_preferences')) {
            Schema::create('foodwaste_user_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->boolean('is_foodwaste_bank')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);
            });
        }

        if (!Schema::hasTable('welfare_fund_ledgers')) {
            Schema::create('welfare_fund_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('payout_id')->nullable()->index();
                $table->decimal('amount', 12, 2)->default(0.00);
                $table->decimal('balance_after', 12, 2)->default(0.00);
                $table->string('type', 32)->index();
                $table->string('reference_type', 64)->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }
}
