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
        Schema::create('place_galleries', function (Blueprint $table) {
            $table->id('gallery_id');
            $table->foreignId('place_id')->references('place_id')->on('places')->onDelete('cascade');
            $table->string('place_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_galleries');
    }
};
