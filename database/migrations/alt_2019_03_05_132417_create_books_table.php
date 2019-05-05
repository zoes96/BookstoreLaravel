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
            $table->bigIncrements('id');
            //isbn is unique
            $table->string('isbn')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('published')->nullable();
            $table->integer('rating')->default('1');
            $table->text('description')->nullable();

            // foreign key zu user
            $table->integer('user_id')->unsigned();
            // Relation muss nicht genauer definiert werden, weil cascade nicht benötigt wird -> wenn Buch gelöscht wird, soll User erhalten bleiben

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
