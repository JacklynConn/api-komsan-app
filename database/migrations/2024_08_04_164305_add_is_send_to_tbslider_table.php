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
        Schema::table('tbslider', function (Blueprint $table) {
            $table->boolean('is_send')->default(0)->after('active_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbslider', function (Blueprint $table) {
            $table->dropColumn('is_send');
        });
    }
};
