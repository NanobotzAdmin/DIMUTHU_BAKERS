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
        Schema::table('ad_routes', function (Blueprint $blueprint) {
            $blueprint->unsignedBigInteger('sm_superviser_id')->nullable()->after('agent_id');
            
            // Add foreign key constraint if the table exists
            if (Schema::hasTable('sm_superviser')) {
                $blueprint->foreign('sm_superviser_id')->references('id')->on('sm_superviser')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_routes', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['sm_superviser_id']);
            $blueprint->dropColumn('sm_superviser_id');
        });
    }
};
