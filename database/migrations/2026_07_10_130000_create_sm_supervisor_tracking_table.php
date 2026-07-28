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
        Schema::create('sm_supervisor_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('superviser_id')->constrained('sm_superviser')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('ad_agent')->onDelete('set null');
            $table->decimal('lat', 10, 8);
            $table->decimal('long', 11, 8);
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_supervisor_tracking');
    }
};
