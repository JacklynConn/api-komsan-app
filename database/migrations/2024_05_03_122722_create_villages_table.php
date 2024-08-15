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
        Schema::create('villages', function (Blueprint $table) {
            $table->id('village_code');
            $table->foreignId('commune_code')->references('commune_code')->on('communes')->onDelete('cascade');
            $table->foreignId('district_code')->references('district_code')->on('districts')->onDelete('cascade');
            $table->foreignId('province_code')->references('province_code')->on('provinces')->onDelete('cascade');
            $table->string('village_namekh');
            $table->string('village_nameen');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
