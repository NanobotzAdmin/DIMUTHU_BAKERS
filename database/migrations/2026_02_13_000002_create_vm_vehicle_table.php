<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vm_vehicle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('vehicle_number');
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('vehicle_image')->nullable();
            $table->integer('status')->default(1); // 1: Active, 2: Inactive
            $table->timestamps();

            // Foreign key to ad_agent
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vm_vehicle');
    }
};
