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
            $table->dropColumn('recurrence_pattern');
        });
        
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->tinyInteger('recurrence_pattern')->nullable()->after('end_time')->comment('1=Daily, 2=Weekly, 3=Monthly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->dropColumn('recurrence_pattern');
        });
        
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->string('recurrence_pattern', 50)->nullable()->after('end_time');
        });
    }
};
