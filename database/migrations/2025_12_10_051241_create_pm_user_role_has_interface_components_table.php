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
        Schema::create('pm_user_role_has_interface_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_user_role_id');
            $table->unsignedBigInteger('pm_interface_components_id');
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->timestamps();

            $table->foreign('pm_user_role_id', 'pm_urhic_pm_ur_id_foreign')->references('id')->on('pm_user_role')->onDelete('cascade');
            $table->foreign('pm_interface_components_id', 'pm_urhic_pm_ic_id_foreign')->references('id')->on('pm_interface_components')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_user_role_has_interface_components');
    }
};
