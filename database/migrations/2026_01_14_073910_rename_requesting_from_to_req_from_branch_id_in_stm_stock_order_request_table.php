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
        Schema::table('stm_stock_order_request', function (Blueprint $table) {
            $table->renameColumn('requesting_from', 'req_from_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_stock_order_request', function (Blueprint $table) {
            $table->renameColumn('req_from_branch_id', 'requesting_from');
        });
    }
};
