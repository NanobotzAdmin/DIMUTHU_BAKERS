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
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->decimal('monthly_sales_target', 10, 2)->nullable();
        });

        Schema::create('ad_agent_has_category_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('pm_product_category_id');
            $table->decimal('target_amount', 10, 2)->nullable();
            $table->decimal('target_percentage', 6, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('restrict');
            $table->foreign('pm_product_category_id')->references('id')->on('pm_product_category')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
        });

        Schema::create('ad_agent_has_item_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('pm_product_item_id');
            $table->decimal('target_amount', 10, 2)->nullable();
            $table->decimal('target_percentage', 6, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('restrict');
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->dropColumn('monthly_sales_target');
        });

        Schema::dropIfExists('ad_agent_has_category_targets');
        Schema::dropIfExists('ad_agent_has_item_targets');
    }
};
