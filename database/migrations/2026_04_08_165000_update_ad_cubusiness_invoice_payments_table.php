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
        Schema::table('ad_cubusiness_invoice_payments', function (Blueprint $table) {
            // Make invoice link nullable for grouped payments
            $table->unsignedBigInteger('ad_cubusiness_has_invoice_id')->nullable()->change();
            
            // Add business link to track who the payment is for
            if (!Schema::hasColumn('ad_cubusiness_invoice_payments', 'ad_customer_has_business_id')) {
                $table->unsignedBigInteger('ad_customer_has_business_id')->after('payment_type')->nullable();
                $table->foreign('ad_customer_has_business_id', 'fk_payment_business_id')
                    ->references('id')
                    ->on('ad_customer_has_business')
                    ->onDelete('restrict');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_cubusiness_invoice_payments', function (Blueprint $table) {
            $table->dropForeign('fk_payment_business_id');
            $table->dropColumn('ad_customer_has_business_id');
            $table->unsignedBigInteger('ad_cubusiness_has_invoice_id')->nullable(false)->change();
        });
    }
};
