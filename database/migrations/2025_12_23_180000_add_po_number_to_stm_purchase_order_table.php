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
        Schema::table('stm_purchase_order', function (Blueprint $table) {
            $table->string('po_number', 50)->unique()->nullable()->after('id');
            $table->integer('status')->after('po_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_purchase_order', function (Blueprint $table) {
            $table->dropColumn('po_number');
            $table->dropColumn('status');
        });
    }
};
