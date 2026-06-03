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
        Schema::create('em_email_send', function (Blueprint $table) {
            $table->id();
            $table->string('email_address', 150);
            $table->integer('process_id');
            $table->integer('template_id')->nullable();
            $table->text('email_content');
            $table->string('email_subject', 250);
            $table->string('send_response', 250)->nullable();
            $table->tinyInteger('status')->default(0); // 0 = Pending, 1 = Sent, 2 = Failed
            $table->integer('created_by')->nullable();
            $table->datetime('created_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->string('attachment_path', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('em_email_send');
    }
};
