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
        Schema::rename('restaurant__ratings', 'restaurant_ratings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('restaurant_ratings', 'restaurant__ratings');
    }
};
