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
        Schema::create('place_ratings', function (Blueprint $table) {
            $table->id('place_rating_id');
            $table->foreignId('place_id')->references('place_id')->on('places')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_ratings');
    }
};
