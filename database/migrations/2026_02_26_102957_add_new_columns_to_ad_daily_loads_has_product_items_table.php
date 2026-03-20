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
        Schema::table('ad_daily_loads_has_product_items', function (Blueprint $table) {
            $table->unsignedBigInteger('stm_branch_stock_id')->nullable()->index()->after('daily_load_id');
            $table->renameColumn('quantity', 'loaded_qty');
            $table->decimal('available_quantity', 10, 3)->nullable()->after('loaded_qty');

            $table->foreign('stm_branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_daily_loads_has_product_items', function (Blueprint $table) {
            $table->dropColumn('stm_branch_stock_id');
            $table->renameColumn('loaded_qty', 'quantity');
            $table->dropColumn('available_quantity');
            $table->dropForeign(['stm_branch_stock_id']);
        });
    }
};
