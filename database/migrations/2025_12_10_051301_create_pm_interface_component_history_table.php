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
        Schema::create('pm_interface_component_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_interface_components_id');
            $table->unsignedBigInteger('pm_user_role_id');
            $table->unsignedBigInteger('um_user_id');
            $table->dateTime('created_date')->nullable();
            $table->string('interface_name',45)->nullable();
            $table->string('component_name',45)->nullable();
            $table->integer('is_added')->nullable();
            $table->integer('is_removed')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->timestamps();

            $table->foreign('pm_interface_components_id', 'pm_int_comp_hist_comp_id_foreign')->references('id')->on('pm_interface_components')->onDelete('cascade');
            $table->foreign('pm_user_role_id')->references('id')->on('pm_user_role')->onDelete('cascade');
            $table->foreign('um_user_id')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_interface_component_history');
    }
};
