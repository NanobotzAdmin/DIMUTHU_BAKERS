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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->string('name'); // Ingredient name
            $table->decimal('quantity', 10, 2); // Quantity
            $table->string('unit'); // Unit of measurement (g, ml, pcs, etc.)
            $table->decimal('cost_per_unit', 10, 2)->default(0.00); // Cost per unit
            $table->string('type')->default('ingredient'); // ingredient, waste_input, etc.
            $table->integer('sort_order')->default(0); // Order of ingredients
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
