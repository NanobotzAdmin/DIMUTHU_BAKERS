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
        Schema::create('ad_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('daily_load_id')->nullable();
            $table->string('settlement_number')->unique();
            $table->date('settlement_date');
            $table->decimal('total_sales', 22, 2)->default(0);
            $table->decimal('cash_sales', 22, 2)->default(0);
            $table->decimal('credit_sales', 22, 2)->default(0);
            $table->decimal('cheque_sales', 22, 2)->default(0);
            $table->decimal('commission_earned', 22, 2)->default(0);
            $table->string('status', 45)->default('pending'); // approved, pending, cancelled
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('cascade');
            $table->foreign('daily_load_id')->references('id')->on('ad_daily_loads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_settlements');
    }
};
