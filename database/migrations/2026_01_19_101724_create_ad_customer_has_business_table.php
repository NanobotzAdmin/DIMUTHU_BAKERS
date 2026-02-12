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
        Schema::create('ad_customer_has_business', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('contact_person_name');
            $table->string('contact_person_phone');
            $table->string('contact_person_email')->nullable();

            // Business Details
            $table->tinyInteger('b2b_customer_type')->nullable()->comment('1=Wholesale, 2=Retail, etc.');
            $table->tinyInteger('payment_terms')->default(1)->comment('Byte value for payment terms');
            $table->tinyInteger('visit_schedule')->default(1)->comment('1=Weekly, etc.');
            $table->text('preferred_visit_days')->nullable()->comment('JSON Array of days');

            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->integer('payment_terms_days')->nullable()->comment('Net days e.g. 30');

            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('cm_customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_customer_has_business');
    }
};
