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
        Schema::table('ad_credit_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('ad_agent_payment_id')->nullable()->after('ad_customer_has_business_id');
            $table->foreign('ad_agent_payment_id')->references('id')->on('ad_agent_payments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_credit_notes', function (Blueprint $table) {
            $table->dropForeign(['ad_agent_payment_id']);
            $table->dropColumn('ad_agent_payment_id');
        });
    }
};
