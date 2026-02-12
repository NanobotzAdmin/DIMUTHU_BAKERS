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
        Schema::create('pln_production_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pln_resource_id')->constrained('pln_resources')->onDelete('cascade');
            // Link to order request if applicable
            $table->foreignId('stm_order_request_id')->nullable()->constrained('stm_order_requests')->onDelete('set null');
            // Product being produced
            $table->foreignId('pm_product_item_id')->nullable()->constrained('pm_product_item')->onDelete('set null');
            
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->double('quantity')->default(0);
            $table->string('status')->default('scheduled'); // scheduled, in-progress, completed
            $table->text('notes')->nullable();
            
            // User who created the schedule
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pln_production_schedules');
    }
};
