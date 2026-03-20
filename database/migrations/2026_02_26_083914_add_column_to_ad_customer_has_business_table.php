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
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('customer_id');
            $table->string('customer_image')->nullable()->after('business_name');
            $table->string('address')->nullable()->after('business_name');
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->dropColumn('business_name');
            $table->dropColumn('customer_image');
            $table->dropColumn('address');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
