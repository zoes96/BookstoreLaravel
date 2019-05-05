<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //fk field or relation - model name lowercase + "_id"
            $table->integer('adress_id')->unsigned()->index();
            $table->foreign('adress_id')
                ->references('id')->on('adresses'); // ohne cascade, da Land nicht gelöscht werden darf, wenn ein User dranhängt

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
