<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_credit_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('rejected_by')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            
            $table->foreign('rejected_by')->references('id')->on('um_user')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ad_credit_notes', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['rejected_by', 'rejected_at']);
        });
    }
};
