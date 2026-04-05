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
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->string('vehicle_category')->nullable()->after('address');
            $table->decimal('invoicing_commission_rate', 5, 2)->default(15.00)->after('commission_rate');
            $table->decimal('target_commission_rate', 5, 2)->default(5.00)->after('invoicing_commission_rate');
            $table->decimal('achievement_threshold', 5, 2)->default(80.00)->after('target_commission_rate');
            $table->decimal('reduced_target_commission_rate', 5, 2)->default(4.00)->after('achievement_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_category',
                'invoicing_commission_rate',
                'target_commission_rate',
                'achievement_threshold',
                'reduced_target_commission_rate'
            ]);
        });
    }
};
