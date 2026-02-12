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
        Schema::create('pm_product_item_has_product_types', function (Blueprint $table) {
            $table->unsignedBigInteger('product_item_id');
            $table->unsignedBigInteger('product_type_id');

            // Create composite primary key
            $table->primary(['product_item_id', 'product_type_id']);

            // Add foreign key constraints
            $table->foreign('product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('product_type_id')->references('id')->on('pm_product_type')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_product_item_has_product_types');
    }
};
