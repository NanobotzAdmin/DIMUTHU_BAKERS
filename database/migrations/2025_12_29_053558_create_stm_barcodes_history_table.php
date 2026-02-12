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
        Schema::create('stm_barcodes_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barcode_id')->index();
            $table->unsignedBigInteger('created_by')->index();
            $table->string('action', 100);
            $table->text('description');
            $table->timestamps();

            $table->foreign('barcode_id')->references('id')->on('stm_barcodes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_barcodes_history');
    }
};
