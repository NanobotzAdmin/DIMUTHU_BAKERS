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
        Schema::create('stm_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stm_stock_in_id')->index();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->date('stock_date');
            $table->double('quantity', 16, 3)->nullable();
            $table->double('costing_price', 22, 2)->nullable();
            $table->double('selling_price', 22, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('expire_period', 45)->nullable();
            $table->string('batch_number', 45)->nullable();
            $table->string('rack_number', 45)->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            // Foreign keys
            $table->foreign('stm_stock_in_id')->references('id')->on('stm_stock_in')->onDelete('cascade');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_stock');
    }
};
