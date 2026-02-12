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
            // We cannot easily revert to NOT NULL without data loss or default values logic, 
            // but for strict reversibility we'd assume data is filled.
            // Leaving as nullable for safety in down migration or adding nullable is generally safe.
        });
    }
};
