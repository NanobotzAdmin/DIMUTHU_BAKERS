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
        Schema::table('stm_stock', function (Blueprint $table) {
            $table->decimal('qty_in_unit', 10, 2)->default(0)->after('quantity');
        });

        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->decimal('qty_in_unit', 10, 2)->default(0)->after('quantity');
        });

        Schema::table('stm_barcodes', function (Blueprint $table) {
            // stm_barcodes often doesn't have 'quantity', so we just add it.
            // If it represents 1 item, we still might want to store the unit value (e.g. 500g).
            $table->decimal('qty_in_unit', 10, 2)->default(0)->after('pm_product_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_stock', function (Blueprint $table) {
            $table->dropColumn('qty_in_unit');
        });

        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->dropColumn('qty_in_unit');
        });

        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropColumn('qty_in_unit');
        });
    }
};
