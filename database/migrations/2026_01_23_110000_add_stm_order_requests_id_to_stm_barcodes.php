<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStmOrderRequestsIdToStmBarcodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            if (!Schema::hasColumn('stm_barcodes', 'stm_order_requests_id')) {
                $table->unsignedBigInteger('stm_order_requests_id')->nullable()->after('stm_stock_order_request_id');
                // Optional: Add foreign key constraint if needed, but not strictly asked for.
                $table->foreign('stm_order_requests_id')->references('id')->on('stm_order_requests')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            if (Schema::hasColumn('stm_barcodes', 'stm_order_requests_id')) {
                $table->dropColumn('stm_order_requests_id');
            }
        });
    }
}
