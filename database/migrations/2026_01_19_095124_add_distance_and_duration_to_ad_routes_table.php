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
        Schema::table('ad_routes', function (Blueprint $table) {
            $table->decimal('target_distance_km', 8, 2)->nullable()->after('description')->comment('Target distance for the route in km');
            $table->decimal('target_duration_hours', 5, 2)->nullable()->after('target_distance_km')->comment('Target duration for the route in hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_routes', function (Blueprint $table) {
            $table->dropColumn(['target_distance_km', 'target_duration_hours']);
        });
    }
};
