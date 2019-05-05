<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            //isbn is unique
            $table->string('isbn')->unique(); //in datenbank mit richtigen constraint anglegen
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('published')->nullable();
            $table->integer('rating')->default('1');
            $table->text('description')->nullable();
            $table->decimal('currentNetto', 5, 2);
            //foreign key for use table
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('books');
    }
}