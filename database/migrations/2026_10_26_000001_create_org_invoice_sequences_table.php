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
        Schema::create('org_invoice_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk');
            $table->unsignedSmallInteger('year');
            $table->unsignedBigInteger('last_running_no')->default(0);
            $table->timestamps();

            $table->unique(['org_id_fk', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_invoice_sequences');
    }
};
