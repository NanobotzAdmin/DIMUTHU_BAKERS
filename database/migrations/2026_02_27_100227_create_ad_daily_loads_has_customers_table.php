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
        Schema::create('ad_daily_loads_has_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_load_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('stop_sequence')->default(0)->comment('Order of delivery');
            $table->integer('status')->default(0)->comment('0: Pending, 1: Visited, 2: Skipped');
            $table->decimal('distance_km', 8, 2)->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('daily_load_id')->references('id')->on('ad_daily_loads')->onDelete('restrict');
            $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_daily_loads_has_customers');
    }
};
