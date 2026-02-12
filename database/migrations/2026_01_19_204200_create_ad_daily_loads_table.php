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
        Schema::create('ad_daily_loads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->index();
            $table->unsignedBigInteger('route_id')->index();
            $table->date('load_date');
            $table->tinyInteger('status')->default(0)->comment('0: Draft, 1: Loaded, 2: Completed');
            $table->boolean('is_mark_as_loaded')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_daily_loads');
    }
};
