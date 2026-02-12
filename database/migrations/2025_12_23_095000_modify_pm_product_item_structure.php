<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('pm_product_item', 'selling_price')) {
                $table->dropColumn('selling_price');
            }
            if (Schema::hasColumn('pm_product_item', 'cost_price')) {
                $table->dropColumn('cost_price');
            }

            // Add new columns
            $table->string('ref_number_auto', 50)->nullable()->unique()->after('status'); // Auto generated GB_XXXXX
            $table->string('reference_number', 100)->nullable()->after('ref_number_auto'); // Manual input
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->dropColumn(['ref_number_auto', 'reference_number']);
        });
    }
};
