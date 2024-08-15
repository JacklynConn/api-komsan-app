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
        Schema::table('hotel_types', function (Blueprint $table) {

            $table->foreignId('cat_hotel_id')->references('cat_hotel_id')->on('category_hotels')->onDelete('cascade')->after('hotel_type_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_types', function (Blueprint $table) {
            $table->dropColumn('cat_hotel_id');
        });
    }
};
