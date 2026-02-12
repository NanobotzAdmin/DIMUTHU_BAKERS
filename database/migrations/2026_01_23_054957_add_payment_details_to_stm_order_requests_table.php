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
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('payment_details');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_order_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_reference', 'paid_amount']);
        });
    }
};
