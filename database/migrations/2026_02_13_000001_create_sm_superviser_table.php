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
        Schema::create('sm_superviser', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('superviser_code')->unique();
            $table->string('superviser_name');
            $table->string('contact_number');
            $table->string('nic_number')->nullable();
            $table->text('address')->nullable();
            $table->integer('status')->default(1); // 1: Active, 2: Inactive
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->timestamps();

            // Foreign keys (if tables exist)
            $table->foreign('user_id')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_superviser');
    }
};
