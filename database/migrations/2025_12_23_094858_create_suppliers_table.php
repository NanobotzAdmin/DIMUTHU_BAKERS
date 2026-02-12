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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active'); // active, inactive, pending-verification
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('on_time_delivery')->default(0); // percentage
            $table->integer('quality_score')->default(0); // percentage
            $table->integer('lead_time')->default(0); // days
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_credit', 15, 2)->default(0);
            $table->string('payment_terms')->default('cash'); // cash, credit-7, credit-15, credit-30, etc.
            $table->json('tags')->nullable(); // JSON array of tags
            $table->json('categories')->nullable(); // JSON array of categories
            $table->json('documents')->nullable(); // JSON array of documents
            $table->json('contracts')->nullable(); // JSON array of contracts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
