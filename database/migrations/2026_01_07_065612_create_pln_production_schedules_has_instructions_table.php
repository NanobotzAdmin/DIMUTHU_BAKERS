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
        Schema::create('pln_schedules_has_instructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_schedule_id');
            $table->unsignedBigInteger('instruction_id');
            $table->integer('status')->default(0);
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('notes',255)->nullable();
            $table->timestamps();

            $table->foreign('production_schedule_id')->references('id')->on('pln_production_schedules')->onDelete('cascade');
            $table->foreign('instruction_id')->references('id')->on('pm_recipe_instructions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pln_production_schedules_has_instructions');
    }
};
