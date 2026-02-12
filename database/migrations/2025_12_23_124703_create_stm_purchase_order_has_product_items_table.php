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
        Schema::create('stm_purchase_order_has_product_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id')->index();
            $table->unsignedBigInteger('product_item_id')->index();
            $table->foreign('purchase_order_id')->references('id')->on('stm_purchase_order')->onDelete('cascade');
            $table->foreign('product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->double('unit_price', 22, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_purchase_order_has_product_items');
    }
};
