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
        Schema::create('ad_credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->string('credit_note_number', 50)->unique();
            $table->date('credit_note_date');
            $table->integer('note_type')->default(1); //1=physical return,2=customer return
            $table->decimal('total_amount', 10, 2);
            $table->integer('status')->default(0); //0=pending,1=approved,2=rejected,3=used
            $table->text('reject_reason')->nullable();
            $table->boolean('is_credit_use')->default(false);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('ad_agent')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('um_user')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('um_user')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_credit_notes');
    }
};
