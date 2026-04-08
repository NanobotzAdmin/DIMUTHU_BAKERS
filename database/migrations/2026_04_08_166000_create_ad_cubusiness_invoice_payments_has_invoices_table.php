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
        Schema::create('ad_cubusiness_invoice_payments_has_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_cubusiness_invoice_payments_id');
            $table->unsignedBigInteger('ad_cubusiness_has_invoice_id');
            $table->double('amount', 22, 2);
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();

            $table->foreign('ad_cubusiness_invoice_payments_id', 'fk_master_payment_id')
                ->references('id')
                ->on('ad_cubusiness_invoice_payments')
                ->onDelete('cascade');
            
            $table->foreign('ad_cubusiness_has_invoice_id', 'fk_target_invoice_id')
                ->references('id')
                ->on('ad_cubusiness_has_invoice')
                ->onDelete('restrict');

            $table->foreign('created_by')->references('id')->on('um_user');
            $table->foreign('updated_by')->references('id')->on('um_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_cubusiness_invoice_payments_has_invoices');
    }
};
