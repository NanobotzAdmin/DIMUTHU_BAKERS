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
        Schema::table('ad_agent_has_monthly_targets', function (Blueprint $table) {
            $table->decimal('monthly_commission', 15, 2)->default(0.00)->after('status');
            $table->tinyInteger('payment_status')->default(0)->comment('0: Pending, 1: Processed, 2: Paid')->after('monthly_commission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent_has_monthly_targets', function (Blueprint $table) {
            $table->dropColumn(['monthly_commission', 'payment_status']);
        });
    }
};
