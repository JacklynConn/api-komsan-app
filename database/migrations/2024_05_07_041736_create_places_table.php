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
        Schema::create('places', function (Blueprint $table) {
            $table->id('place_id');
            $table->foreignId('village_code')->references('village_code')->on('villages')->onDelete('cascade');
            $table->string('place_name');
            $table->string('place_des');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('latitude');
            $table->string('longitude');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
