<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Rooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name_room');
            $table->string('address')->nullable();
            $table->integer('price')->default(0);
            $table->integer('capacity')->default(1);
            $table->string('service')->nullable();
            $table->string('video')->nullable();
            $table->text('text_room')->nullable();
            $table->string('photo_room')->nullable();
            $table->string('coordinates')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
