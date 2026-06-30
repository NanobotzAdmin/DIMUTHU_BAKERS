<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ad_credit_note_histories')) {
            Schema::create('ad_credit_note_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ad_credit_note_id')->index();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->string('action', 100);
                $table->integer('status');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('ad_credit_note_id')->references('id')->on('ad_credit_notes')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_credit_note_histories');
    }
};
