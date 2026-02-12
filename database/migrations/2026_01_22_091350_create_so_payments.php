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
        Schema::create('so_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('so_invoice_id');
            $table->decimal('paid_amount',16,2);
            $table->integer('payment_type');
            $table->string('card_4_digits',45)->nullable();
            $table->string('transaction_id',45)->nullable();
            $table->string('reference',45)->nullable();
            $table->string('gift_card_code',45)->nullable();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('so_invoice_id')->references('id')->on('so_invoice')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_payments');
    }
};
