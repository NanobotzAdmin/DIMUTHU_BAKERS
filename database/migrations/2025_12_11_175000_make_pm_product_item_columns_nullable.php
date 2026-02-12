<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['pm_brands_id']);
            $table->dropForeign(['pm_variation_id']);
            $table->dropForeign(['pm_variation_value_id']);
        });

        // Modify columns to be nullable using raw SQL (doctrine/dbal might not be present)
        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_brands_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_variation_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_variation_value_id BIGINT UNSIGNED NULL');

        Schema::table('pm_product_item', function (Blueprint $table) {
            // Re-add foreign keys
            $table->foreign('pm_brands_id')->references('id')->on('pm_brands')->onDelete('cascade');
            $table->foreign('pm_variation_id')->references('id')->on('pm_variation')->onDelete('cascade');
            $table->foreign('pm_variation_value_id')->references('id')->on('pm_variation_value')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, we'd need to ensure no nulls exist, which we can't guarantee easily.
        // But strictly adhering to down():

        // 1. Delete rows with nulls? Or just update them? Safe to just leave nullable or try update?
        // For now, we will try to make them NOT NULL but it might fail if data exists.

        // Detailed down() omitted for safety to avoid data loss on rollback if not handled carefully.
        // But technically we should reverse metadata.

        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->dropForeign(['pm_brands_id']);
            $table->dropForeign(['pm_variation_id']);
            $table->dropForeign(['pm_variation_value_id']);
        });

        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_brands_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_variation_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE pm_product_item MODIFY COLUMN pm_variation_value_id BIGINT UNSIGNED NOT NULL');

        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->foreign('pm_brands_id')->references('id')->on('pm_brands')->onDelete('cascade');
            $table->foreign('pm_variation_id')->references('id')->on('pm_variation')->onDelete('cascade');
            $table->foreign('pm_variation_value_id')->references('id')->on('pm_variation_value')->onDelete('cascade');
        });
    }
};
