<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStmPurchaseOrderAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stm_purchase_order_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_role')->nullable(); // Snapshot of role at that time
            $table->string('action'); // e.g., 'PO Created', 'PO Approved'
            $table->text('description')->nullable();
            $table->string('previous_status')->nullable();
            $table->string('new_status')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('purchase_order_id')->references('id')->on('stm_purchase_order')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stm_purchase_order_audit');
    }
}
