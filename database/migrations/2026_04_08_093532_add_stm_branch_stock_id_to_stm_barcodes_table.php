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
            $table->unsignedBigInteger('stm_branch_stock_id')->nullable()->after('cubusiness_has_invoice_id');
            $table->foreign('stm_branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropForeign(['stm_branch_stock_id']);
            $table->dropColumn('stm_branch_stock_id');
        });
    }
};
