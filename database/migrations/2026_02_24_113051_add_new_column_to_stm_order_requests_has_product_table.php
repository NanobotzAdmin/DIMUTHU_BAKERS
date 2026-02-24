<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stm_order_requests_has_product', function (Blueprint $table) {
            $table->decimal('confirmed_quantity', 10, 3)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('stm_order_requests_has_product', function (Blueprint $table) {
            $table->dropColumn('confirmed_quantity');
        });
    }
};
