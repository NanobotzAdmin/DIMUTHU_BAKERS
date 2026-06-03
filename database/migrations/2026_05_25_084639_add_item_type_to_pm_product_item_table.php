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
        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->integer('item_type')->default(1)->after('pm_product_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });
    }
};
