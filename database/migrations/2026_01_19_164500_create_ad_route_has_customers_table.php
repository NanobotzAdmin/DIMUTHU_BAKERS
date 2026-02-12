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
        Schema::create('ad_route_has_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('customer_id');

            $table->integer('stop_sequence')->comment('Order of delivery');
            $table->decimal('distance_km', 8, 2)->nullable()->comment('Distance from previous stop');

            $table->timestamps();

            // Composite Primary Key
            $table->primary(['route_id', 'customer_id']);

            // Foreign Keys
            $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_route_has_customers');
    }
};
