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
        Schema::table('pln_production_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('um_branch_id')->nullable();
            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pln_production_schedules', function (Blueprint $table) {
            $table->dropForeign(['um_branch_id']);
            $table->dropColumn('um_branch_id');
        });
    }
};
