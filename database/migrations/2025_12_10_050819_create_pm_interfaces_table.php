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
        Schema::create('pm_interfaces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pm_interface_topic_id');
            $table->string('interface_name',150);
            $table->string('path',255)->nullable();
            $table->string('icon_class',100)->nullable();
            $table->string('tile_class',20)->nullable();
            $table->timestamps();
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();

            $table->foreign('pm_interface_topic_id')->references('id')->on('pm_interface_topic')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_interfaces');
    }
};
