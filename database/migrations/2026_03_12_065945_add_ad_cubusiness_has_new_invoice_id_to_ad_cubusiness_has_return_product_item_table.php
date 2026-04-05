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
        Schema::table('ad_cubusiness_has_return_product_item', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_new_invoice_id')->nullable();

            $table->foreign('ad_new_invoice_id')->references('id')->on('ad_cubusiness_has_invoice')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_cubusiness_has_return_product_item', function (Blueprint $table) {
            $table->dropColumn('ad_new_invoice_id');
        });
    }
};
