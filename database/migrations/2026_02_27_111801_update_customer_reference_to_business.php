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
        Schema::disableForeignKeyConstraints();

        try {
            Schema::table('ad_route_has_customers', function (Blueprint $table) {
                $table->dropForeign(['route_id']);
                $table->dropForeign(['customer_id']);
            });
        } catch (\Exception $e) {
            // Foreign key might not exist or was named differently, ignore.
        }

        Schema::table('ad_route_has_customers', function (Blueprint $table) {
            if (Schema::hasColumn('ad_route_has_customers', 'customer_id')) {
                $table->dropPrimary(['route_id', 'customer_id']);
                $table->dropColumn('customer_id');
            }

            if (!Schema::hasColumn('ad_route_has_customers', 'ad_customer_has_business_id')) {
                $table->unsignedBigInteger('ad_customer_has_business_id')->after('route_id');
                $table->primary(['route_id', 'ad_customer_has_business_id']);
                $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('cascade');
                $table->foreign('ad_customer_has_business_id')->references('id')->on('ad_customer_has_business')->onDelete('restrict');
            }
        });

        try {
            Schema::table('ad_daily_loads_has_customers', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
            });
        } catch (\Exception $e) {
            // Ignore
        }

        Schema::table('ad_daily_loads_has_customers', function (Blueprint $table) {
            if (Schema::hasColumn('ad_daily_loads_has_customers', 'customer_id')) {
                $table->dropColumn('customer_id');
            }

            if (!Schema::hasColumn('ad_daily_loads_has_customers', 'ad_customer_has_business_id')) {
                $table->unsignedBigInteger('ad_customer_has_business_id')->after('daily_load_id');
                $table->foreign('ad_customer_has_business_id')->references('id')->on('ad_customer_has_business')->onDelete('restrict');
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::table('ad_route_has_customers', function (Blueprint $table) {
                $table->dropForeign(['route_id']);
                $table->dropForeign(['ad_customer_has_business_id']);
            });
        } catch (\Exception $e) {
        }

        Schema::table('ad_route_has_customers', function (Blueprint $table) {
            if (Schema::hasColumn('ad_route_has_customers', 'ad_customer_has_business_id')) {
                $table->dropPrimary(['route_id', 'ad_customer_has_business_id']);
                $table->dropColumn('ad_customer_has_business_id');
            }

            if (!Schema::hasColumn('ad_route_has_customers', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->after('route_id');
                $table->primary(['route_id', 'customer_id']);
                $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('cascade');
                $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('cascade');
            }
        });

        try {
            Schema::table('ad_daily_loads_has_customers', function (Blueprint $table) {
                $table->dropForeign(['ad_customer_has_business_id']);
            });
        } catch (\Exception $e) {
        }

        Schema::table('ad_daily_loads_has_customers', function (Blueprint $table) {
            if (Schema::hasColumn('ad_daily_loads_has_customers', 'ad_customer_has_business_id')) {
                $table->dropColumn('ad_customer_has_business_id');
            }

            if (!Schema::hasColumn('ad_daily_loads_has_customers', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->after('daily_load_id');
                $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('restrict');
            }
        });

        Schema::enableForeignKeyConstraints();
    }
};
