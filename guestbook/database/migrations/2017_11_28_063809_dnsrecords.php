<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Dnsrecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('dnsrecords',function(Blueprint $table)
        {

            $table->increments('id');
            $table->unsignedInteger('level');
            $table->ipAddress('ip');
            $table->string('domain');
            $table->string('cname');
            $table->timestamps();
            $table->string('country');
            $table->string('city');
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
