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
        Schema::create('place_types', function (Blueprint $table) {
            $table->id('place_type_id');
            $table->foreignId('cat_place_id')->references('cat_place_id')->on('category_places')->onDelete('cascade');
            $table->foreignId('place_id')->references('place_id')->on('places')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_types');
    }
};
