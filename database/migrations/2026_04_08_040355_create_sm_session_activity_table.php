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
        Schema::create('sm_session_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->string('activity_type');
            $table->string('description');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('sm_session')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_session_activity');
    }
};
