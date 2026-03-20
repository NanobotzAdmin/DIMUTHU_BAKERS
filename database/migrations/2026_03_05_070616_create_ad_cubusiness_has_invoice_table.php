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
        Schema::create('ad_cubusiness_has_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_customer_has_business_id');
            $table->unsignedBigInteger('ad_daily_load_id');
            $table->string('invoice_number')->unique();
            $table->string('inoice_type',45);
            $table->double('invoice_price',22,2);
            $table->double('net_price',22,2);
            $table->double('return_price',22,2);
            $table->double('total_amount_paid',22,2);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('ad_customer_has_business_id')->references('id')->on('ad_customer_has_business')->onDelete('restrict');
            $table->foreign('ad_daily_load_id')->references('id')->on('ad_daily_loads')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_cubusiness_has_invoice');
    }
};
