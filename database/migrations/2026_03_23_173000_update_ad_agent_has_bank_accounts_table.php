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
        Schema::table('ad_agent_has_bank_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_agent_has_bank_accounts', 'account_owner_name')) {
                $table->string('account_owner_name')->nullable()->after('agent_id');
            }
            if (!Schema::hasColumn('ad_agent_has_bank_accounts', 'bank_id')) {
                $table->unsignedBigInteger('bank_id')->nullable()->after('account_owner_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_agent_has_bank_accounts', function (Blueprint $table) {
            $table->dropColumn(['account_owner_name', 'bank_id']);
        });
    }
};
