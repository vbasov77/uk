<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Archive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archive', function (Blueprint $table) {
            $table->id();
            $table->string('name_user')->nullable();
            $table->string('phone_user')->nullable();
            $table->string('email_user')->nullable();
            $table->string('no_in')->nullable();
            $table->string('no_out')->nullable();
            $table->string('otz')->nullable();
            $table->text('more_book')->nullable();
            $table->string('user_info')->nullable();
            $table->integer('summ')->nullable();
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
        Schema::dropIfExists('archive');
    }
}
