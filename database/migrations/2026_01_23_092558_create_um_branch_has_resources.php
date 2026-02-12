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
        Schema::create('um_branch_has_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('um_branch_id');
            $table->unsignedBigInteger('pln_resource_id');
            $table->unsignedBigInteger('pln_department_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('pln_resource_id')->references('id')->on('pln_resources')->onDelete('cascade');
            $table->foreign('pln_department_id')->references('id')->on('pln_departments')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('um_branch_has_resources');
    }
};
