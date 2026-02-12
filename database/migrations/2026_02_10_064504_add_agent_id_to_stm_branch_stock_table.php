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
        // Drop old column if exists (outside closure)
        if (Schema::hasColumn('stm_branch_stock', 'agent_id')) {
            Schema::table('stm_branch_stock', function (Blueprint $table) {
                $table->dropForeign(['agent_id']);
                $table->dropColumn('agent_id');
            });
        }

        // Add new column + foreign key
        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')
                ->nullable()
                ->after('stm_order_request_has_product_id');

            $table->foreign('agent_id')
                ->references('id')
                ->on('ad_agent')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
