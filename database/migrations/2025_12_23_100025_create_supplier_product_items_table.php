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
        Schema::create('supplier_product_items', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('product_item_id');

            // Create composite primary key
            $table->primary(['supplier_id', 'product_item_id']);

            // Add foreign key constraints
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');

            // Add any additional fields if needed (like unit_price, etc.)
            $table->double('unit_price', 22, 2)->nullable();
            $table->string('sku')->nullable();
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_product_items');
    }
};
