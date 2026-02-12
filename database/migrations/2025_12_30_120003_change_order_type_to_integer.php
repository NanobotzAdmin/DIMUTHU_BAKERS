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
            // Drop the existing enum column
            $table->dropColumn('order_type');
        });

        Schema::table('stm_order_requests', function (Blueprint $table) {
            // Add new tinyInteger column
            // 1 = pos_pickup, 2 = special_order, 3 = scheduled_production
            $table->tinyInteger('order_type')->after('customer_id')->comment('1=pos_pickup, 2=special_order, 3=scheduled_production');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->dropColumn('order_type');
        });

        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->enum('order_type', ['pos_pickup', 'special_order', 'scheduled_production'])->after('customer_id');
        });
    }
};
