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
        if (!Schema::hasTable('ad_agent_payment_histories')) {
            Schema::create('ad_agent_payment_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ad_agent_payment_id')->index();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->string('action', 100);
                $table->integer('status');
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('ad_agent_payment_id')->references('id')->on('ad_agent_payments')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_agent_payment_histories');
    }
};
