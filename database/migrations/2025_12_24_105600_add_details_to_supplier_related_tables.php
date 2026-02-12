<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('website')->nullable()->after('address');
            $table->json('bank_details')->nullable()->after('payment_terms');
        });

        Schema::table('supplier_contacts', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('phone');
        });

        Schema::table('supplier_product_items', function (Blueprint $table) {
            $table->decimal('minimum_order', 10, 2)->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['website', 'bank_details']);
        });

        Schema::table('supplier_contacts', function (Blueprint $table) {
            $table->dropColumn('mobile');
        });

        Schema::table('supplier_product_items', function (Blueprint $table) {
            $table->dropColumn('minimum_order');
        });
    }
};
