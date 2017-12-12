<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages',function(Blueprint $table)
        {
            $table->increments('id');
            $table->ipAddress('ip');
            $table->text('body');
            $table->timestamps();
            $table->text('country');
            $table->text('city');
            $table->float('longtitude');
            $table->float('latitude');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
