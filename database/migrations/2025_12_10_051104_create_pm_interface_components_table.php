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
        Schema::create('pm_interface_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_interface_id');
            $table->string('components_name',100);
            $table->string('component_id',100)->nullable();
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->timestamps();

            $table->foreign('pm_interface_id')->references('id')->on('pm_interfaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_interface_components');
    }
};
