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
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->unsignedBigInteger('sm_superviser_id')->nullable()->after('agent_id');
            $table->foreign('sm_superviser_id')->references('id')->on('sm_superviser')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->dropForeign(['sm_superviser_id']);
            $table->dropColumn('sm_superviser_id');
        });
    }
};
