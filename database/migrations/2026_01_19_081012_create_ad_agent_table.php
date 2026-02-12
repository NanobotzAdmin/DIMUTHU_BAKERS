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
        Schema::create('ad_agent', function (Blueprint $table) {
            $table->id();
            $table->string('agent_code')->unique()->comment('Auto-generated agent code');
            $table->string('agent_name');
            $table->tinyInteger('agent_type')->comment('1: Salaried, 2: Commission Only, 3: Credit Based');
            $table->tinyInteger('status')->default(1)->comment('1: Active, 2: Inactive');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('nic_number')->nullable();
            $table->text('address')->nullable();
            
            // Financial Terms
            $table->decimal('base_salary', 10, 2)->nullable()->comment('For salaried agents');
            $table->decimal('commission_rate', 5, 2)->nullable()->comment('Commission percentage');
            $table->decimal('credit_limit', 15, 2)->nullable()->comment('For credit-based agents');
            $table->integer('credit_period_days')->nullable()->comment('Credit period in days');
            
            // Tracking Fields
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_collections', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_agent');
    }
};
