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
        Schema::table('sm_supervisor_tracking', function (Blueprint $table) {
            $table->integer('tracking_type')->default(1)->after('description')->comment('1: 30-min periodic, 2: start trip, 3: customer billing, 4: unload, 5: live tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_supervisor_tracking', function (Blueprint $table) {
            $table->dropColumn('tracking_type');
        });
    }
};
