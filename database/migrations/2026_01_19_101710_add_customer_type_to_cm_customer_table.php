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
        Schema::table('cm_customer', function (Blueprint $table) {
            $table->tinyInteger('customer_type')->default(0)->after('name')->comment('1=B2B, 2=B2C');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cm_customer', function (Blueprint $table) {
            $table->dropColumn('customer_type');
        });
    }
};
