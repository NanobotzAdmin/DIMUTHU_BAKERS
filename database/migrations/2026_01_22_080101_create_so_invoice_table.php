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
        Schema::create('so_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number',45)->unique();
            $table->decimal('total_price',16,2);
            $table->decimal('tax_amount',16,2);
            $table->integer('discount_type');
            $table->decimal('discount_value',16,2);
            $table->decimal('payble_amount',16,2);
            $table->decimal('given_amount',16,2);
            $table->unsignedBigInteger('um_branch_id');
            $table->unsignedBigInteger('cm_customer_id');
            $table->unsignedBigInteger('created_by');
            $table->tinyInteger('status');
            $table->timestamps();

            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('cm_customer_id')->references('id')->on('cm_customer')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_invoice');
    }
};
