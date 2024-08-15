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
        Schema::create('restaurant_galleries', function (Blueprint $table) {
            $table->id("res_gallery_id");
            $table->foreignId("res_id")->references("res_id")->on("restaurants")->onDelete("cascade");
            $table->string("image");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_galleries');
    }
};
