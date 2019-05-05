<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street');
            $table->string('postcode');
            $table->string('city');
            $table->timestamps();

            //fk field or relation - model name lowercase + "_id"
            $table->integer('country_id')->unsigned()->index();
            $table->foreign('country_id')
                ->references('id')->on('countries'); // ohne cascade, da Land nicht gelöscht werden darf, wenn ein User dranhängt
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adresses');
    }
}
