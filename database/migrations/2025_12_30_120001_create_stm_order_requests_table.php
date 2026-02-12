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
        Schema::create('stm_order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->integer('branch_id'); // Outlet/branch identifier
            $table->unsignedBigInteger('customer_id')->nullable()->index(); // Only for special orders
            $table->enum('order_type', ['pos_pickup', 'special_order', 'scheduled_production']);
            $table->dateTime('end_time')->nullable(); // For scheduled orders
            $table->string('recurrence_pattern', 50)->nullable(); // If recurring
            $table->date('end_date')->nullable(); // For recurring orders
            $table->integer('payment_details')->nullable(); // Only for special orders
            $table->string('status', 50)->default('draft');
            $table->decimal('grand_total', 22, 2)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->timestamps();

            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stm_order_requests');
    }
};
