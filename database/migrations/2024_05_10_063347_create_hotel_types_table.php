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
        Schema::create('hotel_types', function (Blueprint $table) {
            $table->id('hotel_type_id');
            $table->foreignId('hotel_id')->references('hotel_id')->on('hotels')->onDelete('cascade');
            $table->string('hotel_type_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_types');
    }
};
