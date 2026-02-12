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
        // 1. Add stm_order_request_has_product_id to stm_branch_stock
        Schema::table('stm_branch_stock', function (Blueprint $table) {

            // Drop column if exists (safe)
            if (Schema::hasColumn('stm_branch_stock', 'stm_order_request_has_product_id')) {
                $table->dropForeign(['stm_order_request_has_product_id']);
                $table->dropColumn('stm_order_request_has_product_id');
            }

            // Add column + foreign key
            $table->unsignedBigInteger('stm_order_request_has_product_id')
                ->nullable()
                ->after('stm_stock_transfer_id');

            $table->foreign('stm_order_request_has_product_id')
                ->references('id')
                ->on('stm_order_requests_has_product')
                ->nullOnDelete();
        });

        // 2. Add agent_id to stm_barcodes
        Schema::table('stm_barcodes', function (Blueprint $table) {

            // Drop column if exists (safe)
            if (Schema::hasColumn('stm_barcodes', 'agent_id')) {
                $table->dropForeign(['agent_id']);
                $table->dropColumn('agent_id');
            }

            // Add column + foreign key
            $table->unsignedBigInteger('agent_id')
                ->nullable()
                ->after('pln_department_id');

            $table->foreign('agent_id')
                ->references('id')
                ->on('ad_agent')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove stm_order_request_has_product_id
        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->dropForeign(['stm_order_request_has_product_id']);
            $table->dropColumn('stm_order_request_has_product_id');
        });

        // Remove agent_id
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
