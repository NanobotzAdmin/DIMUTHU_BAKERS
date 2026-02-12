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
        Schema::create('um_branch_has_department', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('um_branch_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('um_branch_id')->references('id')->on('um_branch');
            $table->foreign('department_id')->references('id')->on('pln_departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('um_branch_has_department');
    }
};
