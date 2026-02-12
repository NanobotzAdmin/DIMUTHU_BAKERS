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
        Schema::create('ad_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_code', 50)->unique()->comment('Auto-generated route code: RT-YYYY-NNNN');
            $table->string('route_name')->comment('Name of the route');
            $table->text('description')->nullable()->comment('Route description');
            $table->unsignedBigInteger('agent_id')->nullable()->comment('Assigned agent');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_routes');
    }
};
