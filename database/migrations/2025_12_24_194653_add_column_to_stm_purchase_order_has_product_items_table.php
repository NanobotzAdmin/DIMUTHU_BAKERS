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
        Schema::table('stm_purchase_order_has_product_items', function (Blueprint $table) {
            $table->double('grn_received_quantity')->nullable();
            $table->tinyInteger('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_purchase_order_has_product_items', function (Blueprint $table) {
            $table->dropColumn('grn_received_quantity');
            $table->dropColumn('is_completed');
        });
    }
};
