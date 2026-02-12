<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing string values to numeric values
        DB::table('pm_recipes')->where('status', 'active')->update(['status' => '2']);
        DB::table('pm_recipes')->where('status', 'inactive')->update(['status' => '1']);
        DB::table('pm_recipes')->where('status', 'draft')->update(['status' => '1']); // draft becomes inactive
        DB::table('pm_recipes')->where('status', 'archived')->update(['status' => '1']); // archived becomes inactive
        
        // Change column type to tinyint
        Schema::table('pm_recipes', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->change(); // 1 = inactive, 2 = active
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Change back to string
        Schema::table('pm_recipes', function (Blueprint $table) {
            $table->string('status')->default('draft')->change();
        });
        
        // Convert numeric values back to strings
        DB::table('pm_recipes')->where('status', '2')->update(['status' => 'active']);
        DB::table('pm_recipes')->where('status', '1')->update(['status' => 'inactive']);
    }
};
