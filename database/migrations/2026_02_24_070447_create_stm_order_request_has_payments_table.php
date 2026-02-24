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
        Schema::create('stm_order_request_has_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stm_order_request_id')->index();
            $table->decimal('payment_amount', 22, 2);
            $table->string('payment_method', 50); // cash, bank_transfer, etc.
            $table->string('payment_reference')->nullable();
            $table->dateTime('payment_date');
            $table->integer('status')->default(1); // 1: Active, 0: Cancelled
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('stm_order_request_id')->references('id')->on('stm_order_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_order_request_has_payments');
    }
};
