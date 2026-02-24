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
            $table->unsignedBigInteger('stm_order_request_id')->nullable()->index()->after('stm_stock_order_request_id');

            // Make stm_stock_order_request_id nullable if it's not already, 
            // as we might be linking to the other order request table
            $table->unsignedBigInteger('stm_stock_order_request_id')->nullable()->change();

            $table->foreign('stm_order_request_id')->references('id')->on('stm_order_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_stock_transfer', function (Blueprint $table) {
            $table->dropForeign(['stm_order_request_id']);
            $table->dropColumn('stm_order_request_id');
        });
    }
};
