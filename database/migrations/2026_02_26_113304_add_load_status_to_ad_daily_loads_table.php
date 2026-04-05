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
            $table->tinyInteger('load_status')->default(1)->after('status')->comment('1: Loading, 2: Loaded, 3: Started, 4: Completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_daily_loads', function (Blueprint $table) {
            $table->dropColumn('load_status');
        });
    }
};
