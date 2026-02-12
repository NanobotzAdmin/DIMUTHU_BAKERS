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
        Schema::table('pm_interface_topic', function (Blueprint $table) {
            $table->boolean('show_in_slidebar')->default(1)->after('section_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_interface_topic', function (Blueprint $table) {
            $table->dropColumn('show_in_slidebar');
        });
    }
};
