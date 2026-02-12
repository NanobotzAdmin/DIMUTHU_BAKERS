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
        Schema::create('ad_daily_loads_has_product_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_load_id')->index();
            $table->unsignedBigInteger('product_item_id')->index();
            $table->decimal('quantity', 16, 3)->default(0);
            $table->decimal('price', 22, 2)->default(0)->comment('Snapshot of selling price at time of loading');
            $table->decimal('total_value', 22, 2)->default(0);
            $table->timestamps();

            $table->foreign('daily_load_id', 'daily_load_fk')->references('id')->on('ad_daily_loads')->onDelete('cascade');
            $table->foreign('product_item_id', 'product_item_fk')->references('id')->on('pm_product_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_daily_loads_has_product_items');
    }
};
