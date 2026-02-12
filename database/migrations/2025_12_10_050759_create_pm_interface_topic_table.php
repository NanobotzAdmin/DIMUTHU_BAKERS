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
        Schema::create('pm_interface_topic', function (Blueprint $table) {
            $table->id();
            $table->string('topic_name',100);
            $table->string('menu_icon',100)->nullable();
            $table->string('section_class',100)->nullable();
            $table->timestamps();
            $table->integer('status');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_interface_topic');
    }
};
