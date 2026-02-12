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
            $table->unsignedBigInteger('pln_department_id')->nullable()->after('um_branch_id');
            $table->foreign('pln_department_id')->references('id')->on('pln_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pln_production_schedules', function (Blueprint $table) {
            $table->dropColumn('pln_department_id');
        });
    }
};
