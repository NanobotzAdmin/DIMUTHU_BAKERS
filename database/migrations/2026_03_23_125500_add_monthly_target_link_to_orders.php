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
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('monthly_target_id')->nullable()->after('agent_id');
            $table->foreign('monthly_target_id')->references('id')->on('ad_agent_has_monthly_targets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->dropForeign(['monthly_target_id']);
            $table->dropColumn('monthly_target_id');
        });
    }
};
