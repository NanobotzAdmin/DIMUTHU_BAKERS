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
        Schema::table('stm_stock_transfer', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_stock_id')->index();
            $table->foreign('branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_stock_transfer', function (Blueprint $table) {
            $table->dropForeign(['branch_stock_id']);
            $table->dropIndex(['branch_stock_id']);
            $table->dropColumn('branch_stock_id');
        });
    }
};
