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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('bread');
            $table->string('yield')->nullable(); // e.g., "24 pcs", "2 loaves"
            $table->string('prep_time')->nullable(); // e.g., "3.5 hrs"
            $table->decimal('cost', 10, 2)->default(0.00); // Cost per batch
            $table->string('status')->default('draft'); // draft, active, archived
            $table->string('version')->default('v1.0');
            $table->boolean('is_waste')->default(false); // For waste recovery recipes
            $table->integer('shelf_life')->nullable(); // Shelf life in days
            $table->string('shelf_life_unit')->default('days');
            $table->json('image_paths')->nullable(); // Store image paths as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
