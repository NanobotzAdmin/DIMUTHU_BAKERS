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
        Schema::create('um_user_has_interface_topic', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('um_user_id');
            $table->unsignedBigInteger('pm_interface_topic_id');
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->timestamps();

            $table->foreign('um_user_id')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('pm_interface_topic_id')->references('id')->on('pm_interface_topic')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('um_user_has_interface_topic');
    }
};
