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
        Schema::create('stm_quotation_has_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stm_quotation_id');
            $table->unsignedBigInteger('pm_product_item_id');
            $table->decimal('quantity', 10, 3);
            $table->decimal('unit_price', 22, 2);
            $table->decimal('subtotal', 22, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign Keys
            $table->foreign('stm_quotation_id')->references('id')->on('stm_quotation')->onDelete('cascade');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_quotation_has_products');
    }
};
