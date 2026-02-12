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
        Schema::create('stm_stock_transfer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->unsignedBigInteger('stm_stock_order_request_id')->index();
            $table->unsignedBigInteger('stm_stock_id')->index();
            $table->string('batch_number',100);
            $table->double('requesting_quantity',22,3);
            $table->double('approved_quantity',22,3);
            $table->dateTime('approved_date');
            $table->unsignedBigInteger('approved_by')->index();
            $table->double('dispatched_quantity',22,3);
            $table->dateTime('dispatched_date');
            $table->unsignedBigInteger('dispatched_by')->index();
            $table->double('received_quantity',22,3);
            $table->dateTime('received_date');
            $table->unsignedBigInteger('received_by')->index();
            $table->timestamps();

            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('stm_stock_order_request_id')->references('id')->on('stm_stock_order_request')->onDelete('cascade');
            $table->foreign('stm_stock_id')->references('id')->on('stm_stock')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('dispatched_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_stock_transfer');
    }
};
