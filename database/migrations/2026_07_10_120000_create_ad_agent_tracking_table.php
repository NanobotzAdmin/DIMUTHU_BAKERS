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
        Schema::create('ad_agent_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('ad_agent')->onDelete('cascade');
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
        Schema::dropIfExists('ad_agent_tracking');
    }
};
