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
        Schema::create('dm_driver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('driver_name');
            $table->string('licence_number');
            $table->date('licences_expire_date');
            $table->string('contact_number');
            $table->tinyInteger('status')->default(1)->comment('1: Active, 2: Inactive');
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_driver');
    }
};
