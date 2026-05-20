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
        Schema::create('ad_credit_notes_has_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('credit_note_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('return_stock_id')->nullable();
            $table->unsignedBigInteger('stm_stock_id');
            $table->unsignedBigInteger('branch_stock_id')->nullable();
            $table->integer('qty');
            $table->decimal('distributor_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('reason')->nullable();
            $table->integer('status')->default(0); //0=pending,1=approved,2=rejected,3=used
            $table->timestamps();

            $table->foreign('credit_note_id')->references('id')->on('ad_credit_notes')->onDelete('restrict');
            $table->foreign('product_id')->references('id')->on('pm_product_item')->onDelete('restrict');
            $table->foreign('return_stock_id')->references('id')->on('ad_return_product_stocks')->onDelete('restrict');
            $table->foreign('stm_stock_id')->references('id')->on('stm_stock')->onDelete('restrict');
            $table->foreign('branch_stock_id')->references('id')->on('stm_branch_stock')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_credit_notes_has_product');
    }
};
