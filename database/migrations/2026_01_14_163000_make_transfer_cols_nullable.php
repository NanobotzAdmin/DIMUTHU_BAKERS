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
        Schema::table('stm_stock_transfer', function (Blueprint $table) {
            // Make ID columns nullable as transfer source varies
            $table->unsignedBigInteger('stm_stock_id')->nullable()->change();
            $table->unsignedBigInteger('branch_stock_id')->nullable()->change();

            // Make status/tracking columns nullable for 'Pending' state
            $table->double('approved_quantity', 22, 3)->nullable()->change();
            $table->dateTime('approved_date')->nullable()->change();
            $table->unsignedBigInteger('approved_by')->nullable()->change();

            $table->double('dispatched_quantity', 22, 3)->nullable()->change();
            $table->dateTime('dispatched_date')->nullable()->change();
            $table->unsignedBigInteger('dispatched_by')->nullable()->change();

            $table->double('received_quantity', 22, 3)->nullable()->change();
            $table->dateTime('received_date')->nullable()->change();
            $table->unsignedBigInteger('received_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_stock_transfer', function (Blueprint $table) {
            // Reverting would require strict assumptions, skipping for safety
        });
    }
};
