<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $targetTable = Schema::hasTable('kp_welfare_payouts') ? 'kp_welfare_payouts' : (Schema::hasTable('welfare_payouts') ? 'welfare_payouts' : null);

        Schema::create('welfare_contributions', function (Blueprint $table) use ($targetTable) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            if ($targetTable) {
                $table->foreignId('payout_id')->nullable()->constrained($targetTable)->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('payout_id')->nullable();
            }
            $table->decimal('amount', 10, 2)->default(60.00);
            $table->enum('payment_method', ['auto_wallet', 'cash', 'transfer', 'pending_deduct'])->default('pending_deduct');
            $table->enum('status', ['pending', 'paid', 'exempted'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('slip_img')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welfare_contributions');
    }
};