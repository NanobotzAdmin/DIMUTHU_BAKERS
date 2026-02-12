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
        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->unsignedBigInteger('pln_department_id')->after('um_branch_id')->nullable();
            $table->foreign('pln_department_id')->references('id')->on('pln_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stm_branch_stock', function (Blueprint $table) {
            $table->dropForeign(['pln_department_id']);
            $table->dropIndex(['pln_department_id']);
            $table->dropColumn('pln_department_id');
        });
    }
};
