<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('Offen');
            $table->string('comment')->nullable();

            //fk field or relation - model name lowercase + "_id"
            $table->integer('order_id')->unsigned()->index();
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade'); // cascade, damit States gelöscht werden, wenn die Bestellung gelöscht wird

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
        Schema::dropIfExists('states');
    }
}
