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
        Schema::create('em_process_has_email_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('process_id');
            $table->string('email_address', 150);
            $table->integer('um_user_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('em_process_has_email_addresses');
    }
};
