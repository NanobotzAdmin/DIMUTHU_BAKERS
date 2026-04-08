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
        Schema::table('ad_agent_has_item_targets', function (Blueprint $table) {
            $table->decimal('target_qty', 15, 2)->nullable()->after('pm_product_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent_has_item_targets', function (Blueprint $table) {
            $table->dropColumn('target_qty');
        });
    }
};
