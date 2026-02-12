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
        Schema::table('pln_production_schedules', function (Blueprint $table) {
            $table->integer('actual_output')->nullable();
            $table->tinyInteger('is_waste')->nullable();
            $table->string('waste_reason')->nullable();
            $table->text('quality_note')->nullable();
            $table->string('quality_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pln_production_schedules', function (Blueprint $table) {
            $table->dropColumn('actual_output');
            $table->dropColumn('is_waste');
            $table->dropColumn('waste_reason');
            $table->dropColumn('quality_note');
            $table->dropColumn('quality_photo_path');
        });
    }
};
