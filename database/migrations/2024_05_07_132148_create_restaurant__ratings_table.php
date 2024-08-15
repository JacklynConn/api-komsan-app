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
        Schema::create('restaurant__ratings', function (Blueprint $table) {
            $table->id('res_rating_id');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('res_id')->references('res_id')->on('restaurants')->onDelete('cascade');
            $table->float('rating');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant__ratings');
    }
};
