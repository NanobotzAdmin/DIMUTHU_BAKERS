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
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropColumn('cost_per_unit');
            $table->boolean('is_aged')->default(false);
            $table->integer('aged_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->decimal('cost_per_unit', 10, 2)->default(0.00);
            $table->dropColumn('is_aged');
            $table->dropColumn('aged_days');
        });
    }
};
