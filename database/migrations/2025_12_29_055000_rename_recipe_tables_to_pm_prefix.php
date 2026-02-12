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
        // Drop old tables in reverse order (child tables first)
        Schema::dropIfExists('recipe_byproduct_nrvs');
        Schema::dropIfExists('recipe_byproducts');
        Schema::dropIfExists('recipe_instructions');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipes');

        // Create new pm_recipes table
        Schema::create('pm_recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('bread');
            $table->json('categories')->nullable();
            $table->string('yield')->nullable(); // e.g., "24 pcs", "2 loaves"
            $table->string('prep_time')->nullable(); // e.g., "3.5 hrs"
            $table->decimal('cost', 10, 2)->default(0.00); // Cost per batch
            $table->string('status')->default('draft'); // draft, active, archived
            $table->string('version')->default('v1.0');
            $table->boolean('is_waste')->default(false); // For waste recovery recipes
            $table->integer('shelf_life')->nullable(); // Shelf life in days
            $table->string('shelf_life_unit')->default('days');
            $table->json('image_paths')->nullable(); // Store image paths as JSON
            $table->unsignedBigInteger('product_item_id')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('product_item_id')->references('id')->on('pm_product_item')->nullOnDelete();
        });

        // Create new pm_recipe_ingredients table
        Schema::create('pm_recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('pm_recipes')->onDelete('cascade');
            $table->unsignedBigInteger('product_item_id')->nullable();
            $table->string('name'); // Ingredient name
            $table->decimal('quantity', 10, 2); // Quantity
            $table->string('unit'); // Unit of measurement (g, ml, pcs, etc.)
            $table->string('type')->default('ingredient'); // ingredient, waste_input, etc.
            $table->integer('sort_order')->default(0); // Order of ingredients
            $table->boolean('is_aged')->default(false);
            $table->integer('aged_days')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('product_item_id')->references('id')->on('pm_product_item')->nullOnDelete();
        });

        // Create new pm_recipe_instructions table
        Schema::create('pm_recipe_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('pm_recipes')->onDelete('cascade');
            $table->text('step_description');
            $table->integer('step_number');
            $table->integer('sort_order')->default(0); // Order of instructions
            $table->timestamps();
        });

        // Create new pm_recipe_byproducts table
        Schema::create('pm_recipe_byproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('pm_recipes')->onDelete('cascade');
            $table->foreignId('product_item_id')->nullable()->constrained('pm_product_item')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->timestamps();
        });

        // Create new pm_recipe_byproduct_nrvs table
        Schema::create('pm_recipe_byproduct_nrvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_byproduct_id')->constrained('pm_recipe_byproducts')->onDelete('cascade');
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
        // Drop new tables in reverse order
        Schema::dropIfExists('pm_recipe_byproduct_nrvs');
        Schema::dropIfExists('pm_recipe_byproducts');
        Schema::dropIfExists('pm_recipe_instructions');
        Schema::dropIfExists('pm_recipe_ingredients');
        Schema::dropIfExists('pm_recipes');

        // Recreate old tables (basic structure for rollback)
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('bread');
            $table->string('yield')->nullable();
            $table->string('prep_time')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->string('status')->default('draft');
            $table->string('version')->default('v1.0');
            $table->boolean('is_waste')->default(false);
            $table->integer('shelf_life')->nullable();
            $table->string('shelf_life_unit')->default('days');
            $table->json('image_paths')->nullable();
            $table->timestamps();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->string('name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->string('type')->default('ingredient');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('recipe_instructions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
            $table->text('step_description');
            $table->integer('step_number');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('recipe_byproducts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_item_id')->nullable()->constrained('pm_product_item')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit')->default('kg');
            $table->timestamps();
        });

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
};
