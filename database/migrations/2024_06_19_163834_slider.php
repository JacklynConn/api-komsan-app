<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tbslider', function (Blueprint $table) {
            $table->id('idslider');
            $table->string('title');
            $table->string('image');
            $table->text('description');
            $table->integer('type'); // 0 = free post 1 = place, 2 = hotel, 3 = restaurant
            $table->integer('relatedId')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active_status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tbslider');
    }
};
