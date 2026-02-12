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
            $table->double('selling_price')->nullable()->after('bin_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            if (Schema::hasColumn('pm_product_item', 'selling_price')) {
                $table->dropColumn('selling_price');
            }
        });
    }
};
