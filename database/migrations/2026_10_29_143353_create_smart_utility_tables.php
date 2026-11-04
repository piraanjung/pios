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
        // 1. Config / Settings for Smart Utility GIS
        Schema::create('smart_utility_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk')->nullable()->index();
            $table->string('org_name')->default('อบต.เสม็ดใต้');
            $table->string('area_name')->nullable();
            $table->string('elec_prefix', 10)->default('SMT');
            $table->string('water_prefix', 10)->default('WTR');
            $table->string('road_prefix', 10)->default('RD');
            $table->string('bldg_prefix', 10)->default('BD');
            $table->decimal('center_lat', 10, 6)->default(13.690000);
            $table->decimal('center_lng', 10, 6)->default(101.200000);
            $table->string('staff_pass')->default('3350553');
            $table->json('extra')->nullable();
            $table->timestamps();
        });

        // 2. Map Items / Pins (Poles, Meters, Valves, Tanks, Leaks, Road Issues, Buildings)
        Schema::create('smart_utility_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk')->nullable()->index();
            $table->string('module', 20)->index(); // elec, water, road, bldg
            $table->string('item_code', 50)->index(); // SMT-001, WTR-001, RD-001, BD-001
            $table->string('name')->nullable();
            $table->string('item_type', 50)->nullable(); // pole, meter, valve, tank, leak, issue, building
            $table->string('category', 100)->nullable();
            $table->string('village', 50)->nullable();
            $table->decimal('lat', 10, 6);
            $table->decimal('lng', 10, 6);
            $table->string('status', 30)->default('ok'); // ok, fault, fixing, etc.
            $table->string('condition', 30)->nullable(); // good, fair, poor, danger (for buildings)
            $table->string('year_be', 10)->nullable();
            $table->decimal('area_sqm', 12, 2)->nullable();
            $table->decimal('value_baht', 14, 2)->nullable();
            $table->string('owner')->nullable();
            $table->text('note')->nullable();
            $table->json('meta')->nullable(); // linked meter_id, etc.
            $table->timestamps();
        });

        // 3. Map Shapes & Annotations (Lines, Zones, Labels, POIs, Villages)
        Schema::create('smart_utility_shapes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk')->nullable()->index();
            $table->string('module', 20)->index(); // elec, water, road, bldg
            $table->string('shape_type', 30); // line, zone, label, poi, village
            $table->string('kind', 50)->nullable(); // road, alley, lane, path, canal, pipeMain, pipeSub, fence, etc.
            $table->string('name')->nullable();
            $table->json('coordinates'); // [[lat, lng], ...] or [lat, lng]
            $table->float('radius')->nullable(); // for zone
            $table->json('properties')->nullable();
            $table->timestamps();
        });

        // 4. Maintenance / Fault Tickets (Reports with conversation threads and photos)
        Schema::create('smart_utility_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id_fk')->nullable()->index();
            $table->string('module', 20)->index(); // elec, water, road, bldg
            $table->string('ticket_code', 30)->unique(); // E-0001, W-0001, R-0001, B-0001
            $table->string('ref_code', 50)->nullable()->index(); // item_code or meter number
            $table->string('village', 50)->nullable();
            $table->string('location')->nullable();
            $table->text('detail')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->string('status', 30)->default('new')->index(); // new, doing, done, rejected
            $table->decimal('cost', 12, 2)->default(0);
            $table->json('photos')->nullable(); // array of storage URLs or base64
            $table->json('messages')->nullable(); // [{from, text, at}]
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smart_utility_tickets');
        Schema::dropIfExists('smart_utility_shapes');
        Schema::dropIfExists('smart_utility_items');
        Schema::dropIfExists('smart_utility_configs');
    }
};
