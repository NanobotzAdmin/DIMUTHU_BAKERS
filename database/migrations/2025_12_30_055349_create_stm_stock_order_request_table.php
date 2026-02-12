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
        Schema::create('stm_stock_order_request', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 100)->index()->unique();
            $table->unsignedBigInteger('um_branch_id')->index();
            $table->tinyInteger('is_active');
            $table->unsignedBigInteger('requesting_from')->index();
            $table->integer('status');
            $table->integer('priority_level');
            $table->text('notes')->nullable();
            $table->dateTime('scheduled_date');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('um_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('requesting_from')->references('id')->on('um_branch')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_stock_order_request');
    }
};
