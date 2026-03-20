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
        Schema::create('ad_cubusiness_has_product_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_cubusiness_has_invoice_id');
            $table->unsignedBigInteger('pm_product_item_id');
            $table->unsignedBigInteger('stm_branch_stock_id');
            $table->double('quantity', 22, 2);
            $table->double('unit_price', 22, 2);
            $table->double('total_price', 22, 2);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('ad_cubusiness_has_invoice_id', 'fk_cubusiness_product_item_invoice_id')
                ->references('id')
                ->on('ad_cubusiness_has_invoice')
                ->onDelete('restrict');
            $table->foreign('pm_product_item_id', 'fk_product_item_id')
                ->references('id')
                ->on('pm_product_item')
                ->onDelete('restrict');
            $table->foreign('stm_branch_stock_id', 'fk_branch_stock_id')
                ->references('id')
                ->on('stm_branch_stock')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_cubusiness_has_product_item');
    }
};
