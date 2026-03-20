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
        Schema::create('pm_product_category', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->unique();
            $table->string('category_code')->nullable();
            $table->string('category_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
        });

        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->unsignedBigInteger('pm_product_category_id')->nullable();
            $table->foreign('pm_product_category_id')->references('id')->on('pm_product_category')->onDelete('restrict');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_product_item', function (Blueprint $table) {
            $table->dropForeign(['pm_product_category_id']);
            $table->dropColumn('pm_product_category_id');
        });
        Schema::dropIfExists('pm_product_category');
    }
};
