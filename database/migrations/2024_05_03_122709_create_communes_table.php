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
        Schema::create('communes', function (Blueprint $table) {
            $table->id('commune_code');
            $table->foreignId('district_code')->references('district_code')->on('districts')->onDelete('cascade');
            $table->foreignId('province_code')->references('province_code')->on('provinces')->onDelete('cascade');
            $table->string('commune_namekh');
            $table->string('commune_nameen');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};
