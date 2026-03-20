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
        Schema::table('ad_cubusiness_has_invoice', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->default(0)->comment('0: Pending, 1: Partially Complete, 2: Complete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_cubusiness_has_invoice', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
    }
};
