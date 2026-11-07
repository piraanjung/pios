<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inv_transactions', function (Blueprint $table) {
            $column = Schema::hasColumn('inv_transactions', 'user_id_fk')
                ? 'user_id_fk'
                : (Schema::hasColumn('inv_transactions', 'requester_id_fk') ? 'requester_id_fk' : 'id');

            if (!Schema::hasColumn('inv_transactions', 'requester_name')) {
                $table->string('requester_name')->after($column)->nullable();
            }
            if (!Schema::hasColumn('inv_transactions', 'approver_name')) {
                $table->string('approver_name')->after('requester_name')->nullable();
            }
        });
    }

public function down()
{
    Schema::table('inv_transactions', function (Blueprint $table) {
        $table->dropColumn(['requester_name', 'approver_name']);
    });
}
};
