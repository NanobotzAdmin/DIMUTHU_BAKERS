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
        Schema::table('ad_route_has_customers', function (Blueprint $table) {
            $table->decimal('duration_minutes', 8, 2)->nullable()->after('distance_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_route_has_customers', function (Blueprint $table) {
            $table->dropColumn('duration_minutes');
        });
    }
};
