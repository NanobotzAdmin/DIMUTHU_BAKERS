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
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('stm_stock_order_request_id')->nullable();
            $table->foreign('stm_stock_order_request_id')->references('id')->on('stm_stock_order_request')->onDelete('cascade');
            $table->unsignedBigInteger('um_branch_id')->nullable();
            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropForeign(['stm_stock_order_request_id']);
            $table->dropColumn('stm_stock_order_request_id');
            $table->dropForeign(['um_branch_id']);
            $table->dropColumn('um_branch_id');
        });
    }
};
