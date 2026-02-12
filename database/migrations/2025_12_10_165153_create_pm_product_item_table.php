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
        Schema::create('pm_product_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_product_id');
            $table->unsignedBigInteger('pm_brands_id');
            $table->unsignedBigInteger('pm_variation_id');
            $table->unsignedBigInteger('pm_variation_value_id');
            $table->string('product_name',150)->unique()->nullable();
            $table->string('bin_code',45)->unique()->nullable();
            $table->double('selling_price',22,2)->nullable();
            $table->double('cost_price',22,2)->nullable();
            $table->integer('status');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            $table->foreign('pm_product_id')->references('id')->on('pm_product')->onDelete('cascade');
            $table->foreign('pm_brands_id')->references('id')->on('pm_brands')->onDelete('cascade');
            $table->foreign('pm_variation_id')->references('id')->on('pm_variation')->onDelete('cascade');
            $table->foreign('pm_variation_value_id')->references('id')->on('pm_variation_value')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_product_item');
    }
};
