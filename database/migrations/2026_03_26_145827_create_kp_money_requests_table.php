<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('kp_money_requests')) {
            Schema::create('kp_money_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('batch_id_fk')->nullable();
                $table->string('status')->nullable();
                $table->enum('hold_status', ['held', 'released', 'settled'])->default('held');
                $table->string('proxy_name')->nullable();
                $table->string('proxy_id_card', 20)->nullable();
                $table->string('proxy_relationship', 100)->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('signature_path', 255)->nullable();
                $table->enum('payout_method', ['cash', 'transfer'])->default('cash');
                $table->text('payout_note')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('kp_withdraw_batches') && Schema::hasColumn('kp_money_requests', 'batch_id_fk')) {
            Schema::table('kp_money_requests', function (Blueprint $table) {
                $table->foreign('batch_id_fk')->references('id')->on('kp_withdraw_batches')->onDelete('set null');
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('kp_money_requests');
    }
};
