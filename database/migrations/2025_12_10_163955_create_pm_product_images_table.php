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
        Schema::create('pm_product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_product_id');
            $table->string('image_path',255);
            $table->integer('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('pm_product_id')->references('id')->on('pm_product')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_product_images');
    }
};
