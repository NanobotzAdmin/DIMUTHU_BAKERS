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
            $table->unsignedBigInteger('ad_customer_has_business_id')->nullable()->after('agent_id');
            $table->foreign('ad_customer_has_business_id')->references('id')->on('ad_customer_has_business')->onDelete('restrict');
        });

        Schema::table('ad_return_product_stocks', function (Blueprint $table) {
            $table->double('credit_note_added_qty', 22, 3)->default(0)->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_credit_notes', function (Blueprint $table) {
            $table->dropForeign(['ad_customer_has_business_id']);
            $table->dropColumn('ad_customer_has_business_id');
        });

        Schema::table('ad_return_product_stocks', function (Blueprint $table) {
            $table->dropColumn('credit_note_added_qty');
        });
    }
};
