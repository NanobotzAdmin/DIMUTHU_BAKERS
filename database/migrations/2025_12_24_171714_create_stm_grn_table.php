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
        Schema::create('stm_grn', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('purchase_order_id')->index();
            $table->string('grn_number', 45)->unique();
            $table->string('invoice_number', 45)->nullable();
            $table->double('invoice_amount', 22, 2)->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('is_completed');
            $table->tinyInteger('is_active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('purchase_order_id')->references('id')->on('stm_purchase_order')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_grn');
    }
};
