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
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('cubusiness_has_invoice_id')->nullable()->after('id');
            $table->boolean('is_return')->default(false)->after('is_sold');
            $table->foreign('cubusiness_has_invoice_id')->references('id')->on('ad_cubusiness_has_invoice')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_barcodes', function (Blueprint $table) {
            $table->dropForeign(['cubusiness_has_invoice_id']);
            $table->dropColumn('cubusiness_has_invoice_id');
        });
    }
};
