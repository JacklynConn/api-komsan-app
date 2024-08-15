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
        Schema::table('food', function (Blueprint $table) {
            //
            $table->foreignId('res_id')->after('food_id')->references('res_id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            //
            $table->dropColumn('res_id');
        });
    }
};
