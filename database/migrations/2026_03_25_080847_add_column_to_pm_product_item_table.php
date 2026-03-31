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
            $table->decimal('distributor_percentage', 10, 2)->nullable()->after('selling_price');
            $table->decimal('wholesale_percentage', 10, 2)->nullable()->after('distributor_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->dropColumn('distributor_percentage');
            $table->dropColumn('wholesale_percentage');
        });
    }
};
