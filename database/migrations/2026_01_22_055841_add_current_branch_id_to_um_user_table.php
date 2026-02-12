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
        Schema::table('um_user', function (Blueprint $table) {
            $table->unsignedBigInteger('current_branch_id')->nullable()->after('user_role_id');
            $table->foreign('current_branch_id')->references('id')->on('um_branch')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('um_user', function (Blueprint $table) {
            //
        });
    }
};
