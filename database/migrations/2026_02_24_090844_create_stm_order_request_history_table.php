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
        Schema::create('stm_order_request_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_request_id')->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->string('action', 100);
            $table->integer('status');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('order_request_id')->references('id')->on('stm_order_requests')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_order_request_history');
    }
};
