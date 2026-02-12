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
        Schema::create('um_branch', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('um_branch_type_id')->index();
            $table->string('name', 100)->index();
            $table->string('code', 10)->index();
            $table->string('street_address', 255);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('contact_person', 100);
            $table->string('contact_person_phone', 20)->nullable();
            $table->integer('cash_account')->nullable();
            $table->integer('bank_account')->nullable();
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('um_branch_type_id')->references('id')->on('um_branch_type')->onDelete('cascade');
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
        Schema::dropIfExists('um_branch');
    }
};
