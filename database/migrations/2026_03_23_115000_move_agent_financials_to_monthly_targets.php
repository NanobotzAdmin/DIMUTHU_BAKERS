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
        // 1. Add columns to ad_agent_has_monthly_targets
        Schema::table('ad_agent_has_monthly_targets', function (Blueprint $table) {
            $table->decimal('base_salary', 10, 2)->nullable()->after('monthly_sales_target');
            $table->decimal('commission_rate', 5, 2)->nullable()->after('base_salary');
            $table->decimal('invoicing_commission_rate', 5, 2)->default(15.00)->after('commission_rate');
            $table->decimal('target_commission_rate', 5, 2)->default(5.00)->after('invoicing_commission_rate');
            $table->decimal('achievement_threshold', 5, 2)->default(80.00)->after('target_commission_rate');
            $table->decimal('reduced_target_commission_rate', 5, 2)->default(4.00)->after('achievement_threshold');
        });

        // 2. Drop columns from ad_agent
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->dropColumn([
                'base_salary',
                'commission_rate',
                'invoicing_commission_rate',
                'target_commission_rate',
                'achievement_threshold',
                'reduced_target_commission_rate'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Re-add columns to ad_agent
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->decimal('base_salary', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->decimal('invoicing_commission_rate', 5, 2)->default(15.00);
            $table->decimal('target_commission_rate', 5, 2)->default(5.00);
            $table->decimal('achievement_threshold', 5, 2)->default(80.00);
            $table->decimal('reduced_target_commission_rate', 5, 2)->default(4.00);
        });

        // 2. Drop columns from ad_agent_has_monthly_targets
        Schema::table('ad_agent_has_monthly_targets', function (Blueprint $table) {
            $table->dropColumn([
                'base_salary',
                'commission_rate',
                'invoicing_commission_rate',
                'target_commission_rate',
                'achievement_threshold',
                'reduced_target_commission_rate'
            ]);
        });
    }
};
