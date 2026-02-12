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
        Schema::create('pm_user_role', function (Blueprint $table) {
            $table->id();
            $table->string('user_role_name',100);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->timestamps();
        });

        Schema::create('um_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_role_id')->nullable()->constrained('pm_user_role');
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('user_name',100);
            $table->string('user_password',300);
            $table->string('contact_no',255);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('remark1',150)->nullable();
            $table->string('remark2',150)->nullable();
            $table->integer('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_user_role');
        Schema::dropIfExists('um_user');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
