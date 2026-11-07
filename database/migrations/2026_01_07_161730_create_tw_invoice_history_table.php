<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        if (Schema::hasTable('tw_invoice_history')) {
            return;
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('CREATE TABLE tw_invoice_history AS SELECT * FROM tw_invoice WHERE 0');
        } else {
            DB::statement('CREATE TABLE tw_invoice_history LIKE tw_invoice');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tw_invoice_history');
    }
};
