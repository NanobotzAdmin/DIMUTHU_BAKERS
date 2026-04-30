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
        Schema::create('ad_return_product_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_product_item_id')->index();
            $table->unsignedBigInteger('ad_daily_load_id')->nullable()->index();
            $table->unsignedBigInteger('ad_customer_has_business_id')->nullable()->index();
            $table->double('quantity', 22, 3);
            $table->double('unit_price', 22, 2)->nullable();
            $table->string('reason')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Received, 2: Inspected, 3: Disposed, 4: Restored');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->foreign('pm_product_item_id')->references('id')->on('pm_product_item')->onDelete('cascade');
            $table->foreign('ad_daily_load_id')->references('id')->on('ad_daily_loads')->onDelete('set null');
            $table->foreign('ad_customer_has_business_id', 'fk_return_stock_business')->references('id')->on('ad_customer_has_business')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_return_product_stocks');
    }
};
