<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Booking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('code_book')->nullable();
            $table->integer('room')->nullable();
            $table->string('name_user');
            $table->string('phone_user');
            $table->string('email_user');
            $table->text('date_book');
            $table->string('no_in');
            $table->string('no_out');
            $table->text('more_book');
            $table->string('user_info');
            $table->integer('summ');
            $table->integer('pay')->default(0);
            $table->string('info_pay')->default(0);
            $table->integer('confirmed')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking');
    }
}
