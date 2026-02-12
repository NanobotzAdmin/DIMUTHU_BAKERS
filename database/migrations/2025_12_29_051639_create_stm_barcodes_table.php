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
        Schema::create('stm_barcodes', function (Blueprint $table) {
            $table->id()->index();
            $table->string('barcode',100)->index();
            $table->unsignedBigInteger('stm_stock_id')->index();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->double('selling_price',22,2);
            $table->tinyInteger('is_sold')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('stm_stock_id')->references('id')->on('stm_stock')->onDelete('cascade');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_barcodes');
    }
};
