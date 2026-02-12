<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sanitize status data using raw SQL to avoid casting errors
        // Update any non-numeric status to '0'
        DB::statement("UPDATE stm_order_requests SET status = '0' WHERE status NOT REGEXP '^[0-9]+$'");

        // Fix invalid branch_ids before applying foreign key
        // Get the first valid branch ID
        $validBranchId = DB::table('um_branch')->orderBy('id')->value('id');

        if ($validBranchId) {
            // Update invalid branch_ids using raw SQL for better compatibility
            // Note: We use a raw query because some MySQL versions restrict subquery updates on the same table,
            // but selecting from a DIFFERENT table (um_branch) is fine.
            DB::statement("UPDATE stm_order_requests SET branch_id = ? WHERE branch_id NOT IN (SELECT id FROM um_branch)", [$validBranchId]);
        }


        Schema::table('stm_order_requests', function (Blueprint $table) {
            // Change status to integer with default 0 (Pending/PendingApproval)
            $table->integer('status')->default(0)->change();

            // Change branch_id to unsignedBigInteger and add foreign key
            $table->unsignedBigInteger('branch_id')->change();
            $table->foreign('branch_id')->references('id')->on('um_branch')->onDelete('cascade');

            // Add req_from_branch_id column
            $table->unsignedBigInteger('req_from_branch_id')->nullable()->after('branch_id');
            $table->foreign('req_from_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_order_requests', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['req_from_branch_id']);

            // Revert branch_id to integer
            $table->integer('branch_id')->change();

            // Drop req_from_branch_id
            $table->dropColumn('req_from_branch_id');

            // Revert status to string
            $table->string('status', 50)->default('draft')->change();
        });
    }
};
