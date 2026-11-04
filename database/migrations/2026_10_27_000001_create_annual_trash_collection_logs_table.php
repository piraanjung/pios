<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annual_trash_collection_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk')->index();
            $table->unsignedBigInteger('annual_trash_id')->index();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->string('outcome', 20);
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_trash_collection_logs');
    }
};