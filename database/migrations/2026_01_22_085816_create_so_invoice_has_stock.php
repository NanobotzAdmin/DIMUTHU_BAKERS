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
        Schema::create('so_invoice_has_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('so_invoice_id');
            $table->unsignedBigInteger('pm_product_item_id');
            $table->unsignedBigInteger('stm_branch_stock_id');
            $table->unsignedBigInteger('um_branch_id');
            $table->dateTime('stock_date');
            $table->decimal('grn_price',16,2);
            $table->decimal('selling_price',16,2);
            $table->decimal('invoiced_price',16,2);
            $table->decimal('qty',16,2);
            $table->tinyInteger('is_active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign('so_invoice_id')->references('id')->on('so_invoice')->onDelete('cascade');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('stm_branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('cascade');
            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_invoice_has_stock');
    }
};
