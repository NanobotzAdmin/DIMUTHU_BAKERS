<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ad_daily_loads', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->nullable()->after('route_id');
            $table->unsignedBigInteger('driver_id')->nullable()->after('vehicle_id');
            $table->unsignedBigInteger('supervisor_id')->nullable()->after('driver_id');
            $table->decimal('starting_mileage', 15, 2)->nullable()->after('supervisor_id');

            $table->foreign('vehicle_id')->references('id')->on('vm_vehicle')->onDelete('set null');
            $table->foreign('driver_id')->references('id')->on('dm_driver')->onDelete('set null');
            $table->foreign('supervisor_id')->references('id')->on('sm_superviser')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_daily_loads', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn(['vehicle_id', 'driver_id', 'supervisor_id', 'starting_mileage']);
        });
    }
};
