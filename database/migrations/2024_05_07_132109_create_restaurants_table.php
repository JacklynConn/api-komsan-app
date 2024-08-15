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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id('res_id');
            $table->foreignId('village_code')->references('village_code')->on('villages')->onDelete('cascade');
            $table->foreignId('food_id')->references('food_id')->on('food')->onDelete('cascade');
            $table->string('res_img');
            $table->string('res_name');
            $table->string('res_des');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('res_phone');
            $table->string('res_email');
            $table->string('res_web');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
