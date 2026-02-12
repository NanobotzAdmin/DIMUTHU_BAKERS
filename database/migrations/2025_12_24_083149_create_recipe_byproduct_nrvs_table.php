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
        Schema::create('recipe_byproduct_nrvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_byproduct_id')->constrained('recipe_byproducts')->onDelete('cascade');
            $table->foreignId('product_item_id')->nullable()->constrained('pm_product_item')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->decimal('market_value', 10, 2)->default(0);
            $table->decimal('processing_cost', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_byproduct_nrvs');
    }
};
