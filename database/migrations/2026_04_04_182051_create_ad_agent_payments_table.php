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
        Schema::create('ad_agent_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->decimal('amount', 15, 2);
            $table->integer('payment_method'); // 1: Cash, 2: Card, 3: Bank Transfer
            $table->dateTime('payment_date');
            $table->integer('status')->default(0); // 0: Pending, 1: Approved, 2: Rejected
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('cascade');
        });

        Schema::table('stm_order_request_has_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_agent_payment_id')->nullable()->after('id');
            $table->foreign('ad_agent_payment_id')->references('id')->on('ad_agent_payments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function up_down(): void
    {
        Schema::table('stm_order_request_has_payments', function (Blueprint $table) {
            $table->dropForeign(['ad_agent_payment_id']);
            $table->dropColumn('ad_agent_payment_id');
        });
        Schema::dropIfExists('ad_agent_payments');
    }
};
