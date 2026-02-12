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
        Schema::create('stm_branch_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->unsignedBigInteger('um_branch_id')->index();
            $table->unsignedBigInteger('stm_stock_id')->index();
            $table->unsignedBigInteger('stm_stock_transfer_id')->index();
            $table->double('quantity',22,3);
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('stm_stock_id')->references('id')->on('stm_stock')->onDelete('cascade');
            $table->foreign('stm_stock_transfer_id')->references('id')->on('stm_stock_transfer')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_branch_stock');
    }
};
