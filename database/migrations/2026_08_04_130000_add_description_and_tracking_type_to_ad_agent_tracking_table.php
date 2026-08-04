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
        Schema::table('ad_agent_tracking', function (Blueprint $table) {
            $table->string('description')->nullable()->after('date');
            $table->integer('tracking_type')->default(1)->after('description')->comment('1: 30-min periodic, 2: start trip, 3: customer billing, 4: unload, 5: live tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent_tracking', function (Blueprint $table) {
            $table->dropColumn(['description', 'tracking_type']);
        });
    }
};
