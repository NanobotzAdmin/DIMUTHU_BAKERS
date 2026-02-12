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
        Schema::create('stm_order_requests_has_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stm_order_request_id')->index();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->decimal('quantity', 16, 3);
            $table->decimal('unit_price', 22, 2); // From stock or 0 if no stock
            $table->decimal('subtotal', 22, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('stm_order_request_id')->references('id')->on('stm_order_requests')->onDelete('cascade');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_order_requests_has_product');
    }
};
