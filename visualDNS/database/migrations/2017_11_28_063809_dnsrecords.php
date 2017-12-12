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
            $table->string('belong');
            $table->string('domain');
            $table->ipAddress('ip');
            $table->string('country_name');
            $table->string('region_name');
            $table->string('city');
            $table->float('latitude');
            $table->float('longitude');
            $table->timestamps();

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
