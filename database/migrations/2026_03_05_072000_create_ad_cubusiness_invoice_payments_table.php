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
        Schema::create('ad_cubusiness_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number',45)->unique();
            $table->string('payment_type',45);
            $table->unsignedBigInteger('ad_cubusiness_has_invoice_id');
            $table->date('payment_date');
            $table->date('cheque_date')->nullable();
            $table->string('cheque_number',45)->nullable();
            $table->double('amount',22,2);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('ad_cubusiness_has_invoice_id', 'fk_cubusiness_invoice_id')
                ->references('id')
                ->on('ad_cubusiness_has_invoice')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_cubusiness_invoice_payments');
    }
};
