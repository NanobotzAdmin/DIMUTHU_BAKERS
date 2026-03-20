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
        Schema::table('ad_daily_loads', function (Blueprint $table) {
            $table->decimal('ending_mileage', 15, 2)->nullable()->after('starting_mileage');
            $table->dateTime('unload_time')->nullable()->after('ending_mileage');
        });

        Schema::table('ad_daily_loads_has_product_items', function (Blueprint $table) {
            $table->decimal('unload_qty', 10, 3)->default(0)->after('available_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_daily_loads', function (Blueprint $table) {
            $table->dropColumn(['ending_mileage', 'unload_time']);
        });

        Schema::table('ad_daily_loads_has_product_items', function (Blueprint $table) {
            $table->dropColumn('unload_qty');
        });
    }
};
