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
        // 1. Create ad_agent_has_monthly_targets table
        Schema::create('ad_agent_has_monthly_targets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->integer('target_year');
            $table->integer('target_month');
            $table->decimal('monthly_sales_target', 15, 2)->default(0.00);
            $table->tinyInteger('status')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
            
            $table->unique(['agent_id', 'target_year', 'target_month'], 'agent_monthly_target_unique');
        });

        // 2. Modify ad_agent_has_category_targets
        Schema::table('ad_agent_has_category_targets', function (Blueprint $table) {
            // Drop existing foreign key and column
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
            
            // Add new column and foreign key
            $table->unsignedBigInteger('monthly_target_id')->after('id');
            $table->foreign('monthly_target_id', 'cat_targets_monthly_target_foreign')
                  ->references('id')->on('ad_agent_has_monthly_targets')
                  ->onDelete('cascade');
        });

        // 3. Modify ad_agent_has_item_targets
        Schema::table('ad_agent_has_item_targets', function (Blueprint $table) {
            // Drop existing foreign key and column
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
            
            // Add new column and foreign key
            $table->unsignedBigInteger('monthly_target_id')->after('id');
            $table->foreign('monthly_target_id', 'item_targets_monthly_target_foreign')
                  ->references('id')->on('ad_agent_has_monthly_targets')
                  ->onDelete('cascade');
        });

        // 4. Remove monthly_sales_target from ad_agent
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->dropColumn('monthly_sales_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent', function (Blueprint $table) {
            $table->decimal('monthly_sales_target', 10, 2)->nullable();
        });

        Schema::table('ad_agent_has_item_targets', function (Blueprint $table) {
            $table->dropForeign('item_targets_monthly_target_foreign');
            $table->dropColumn('monthly_target_id');
            
            $table->unsignedBigInteger('agent_id')->after('id');
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('restrict');
        });

        Schema::table('ad_agent_has_category_targets', function (Blueprint $table) {
            $table->dropForeign('cat_targets_monthly_target_foreign');
            $table->dropColumn('monthly_target_id');
            
            $table->unsignedBigInteger('agent_id')->after('id');
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('restrict');
        });

        Schema::dropIfExists('ad_agent_has_monthly_targets');
    }
};
