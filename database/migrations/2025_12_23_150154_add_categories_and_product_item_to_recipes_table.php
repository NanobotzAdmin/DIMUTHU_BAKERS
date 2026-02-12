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
        Schema::table('recipes', function (Blueprint $table) {
            if (!Schema::hasColumn('recipes', 'categories')) {
                $table->string('categories')->nullable()->after('category');
            }
            if (!Schema::hasColumn('recipes', 'product_item_id')) {
                $table->unsignedBigInteger('product_item_id')->nullable()->after('image_paths');
                $table->foreign('product_item_id')->references('id')->on('pm_product_item')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            if (Schema::hasColumn('recipes', 'product_item_id')) {
                $table->dropForeign(['product_item_id']);
                $table->dropColumn('product_item_id');
            }
            if (Schema::hasColumn('recipes', 'categories')) {
                $table->dropColumn('categories');
            }
        });
    }
};
