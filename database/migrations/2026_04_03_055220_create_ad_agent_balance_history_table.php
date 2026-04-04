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
        Schema::create('ad_agent_balance_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('previous_balance', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('new_balance', 15, 2);
            $table->string('type'); // e.g., 'Order Approved', 'Payment Received', 'Adjustment'
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('stm_order_requests')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_agent_balance_history');
    }
};
