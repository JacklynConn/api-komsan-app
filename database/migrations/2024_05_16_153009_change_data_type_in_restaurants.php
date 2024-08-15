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
        Schema::table('restaurants', function (Blueprint $table) {
            //
            $table->string('res_email')->nullable()->change();
            $table->string('res_web')->nullable()->change();
            $table->text('res_des')->change();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            //
            
        });
    }
};
