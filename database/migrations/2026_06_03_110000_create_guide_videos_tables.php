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
        Schema::create('hs_guide_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->integer('display_order')->default(0);
            $table->tinyInteger('status')->default(1); // 1=active, 0=inactive
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('hs_guide_video_has_user_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hs_guide_video_id');
            $table->unsignedBigInteger('pm_user_role_id');
            $table->timestamps();

            $table->foreign('hs_guide_video_id')
                  ->references('id')
                  ->on('hs_guide_videos')
                  ->onDelete('cascade');

            $table->foreign('pm_user_role_id')
                  ->references('id')
                  ->on('pm_user_role')
                  ->onDelete('cascade');

            $table->unique(['hs_guide_video_id', 'pm_user_role_id'], 'guide_video_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hs_guide_video_has_user_roles');
        Schema::dropIfExists('hs_guide_videos');
    }
};
