<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount')->default(1);
            $table->decimal('currentNettoCopy', 5, 2)->nullable();
            $table->timestamps();

            //fk field or relation - model name lowercase + "_id"
            $table->integer('order_id')->unsigned()->index();
            $table->foreign('order_id')
                ->references('id')->on('orders');

            //fk field or relation - model name lowercase + "_id"
            $table->integer('book_id')->unsigned()->index();
            $table->foreign('book_id')
                ->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordersPositions');
    }
}
