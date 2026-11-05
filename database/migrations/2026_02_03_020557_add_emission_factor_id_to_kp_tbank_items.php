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
        Schema::table('kp_tbank_items', function (Blueprint $table) {
            // 1. ✅ สร้าง Column ขึ้นมาก่อน (ต้องเป็น unsignedBigInteger และ nullable เพราะเราใช้ set null)
            if (!Schema::hasColumn('kp_tbank_items', 'ef_id_fk')) {
                $column = $table->unsignedBigInteger('ef_id_fk')->nullable();
                if (Schema::hasColumn('kp_tbank_items', 'org_id_fk')) {
                    $column->after('org_id_fk');
                }
            }

            // 2. ✅ จากนั้นค่อยผูก Foreign Key
            if (Schema::hasTable('emission_factors')) {
                $table->foreign('ef_id_fk')
                    ->references('id')
                    ->on('emission_factors')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kp_tbank_items', function (Blueprint $table) {
            // เวลาลบ ควรลบ Foreign Key ออกก่อน แล้วค่อยลบ Column
            $table->dropForeign(['ef_id_fk']);
            $table->dropColumn('ef_id_fk');
        });
    }
};
