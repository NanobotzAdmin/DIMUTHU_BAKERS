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
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_daily_load_id')->nullable()->index()->after('agent_id');

            $table->foreign('ad_daily_load_id')->references('id')->on('ad_daily_loads')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropColumn('ad_daily_load_id');
            $table->dropForeign(['ad_daily_load_id']);
        });
    }
};
