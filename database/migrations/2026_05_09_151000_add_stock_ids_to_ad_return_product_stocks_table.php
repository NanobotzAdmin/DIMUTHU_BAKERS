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
        Schema::table('ad_return_product_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('stm_stock_id')->nullable()->after('ad_daily_load_id')->index();
            $table->unsignedBigInteger('stm_branch_stock_id')->nullable()->after('stm_stock_id')->index();

            $table->foreign('stm_stock_id')->references('id')->on('stm_stock')->onDelete('restrict');
            $table->foreign('stm_branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_return_product_stocks', function (Blueprint $table) {
            $table->dropForeign(['stm_stock_id']);
            $table->dropForeign(['stm_branch_stock_id']);
            $table->dropColumn(['stm_stock_id', 'stm_branch_stock_id']);
        });
    }
};
