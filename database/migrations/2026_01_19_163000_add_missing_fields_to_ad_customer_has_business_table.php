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
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->unsignedBigInteger('agent_id')->nullable()->after('payment_terms_days');
            $table->unsignedBigInteger('route_id')->nullable()->after('agent_id');
            $table->integer('stop_sequence')->nullable()->after('route_id');
            $table->boolean('allow_credit')->default(false)->after('payment_terms_days');
            $table->time('preferred_time')->nullable()->after('visit_schedule');

            $table->text('special_instructions')->nullable()->after('updated_at');
            $table->text('delivery_instructions')->nullable()->after('special_instructions');
            $table->text('notes')->nullable()->after('delivery_instructions');

            // FKs
            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('set null');
            $table->foreign('route_id')->references('id')->on('ad_routes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_customer_has_business', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['route_id']);
            $table->dropColumn([
                'agent_id',
                'route_id',
                'stop_sequence',
                'allow_credit',
                'preferred_time',
                'special_instructions',
                'delivery_instructions',
                'notes',
            ]);
        });
    }
};
