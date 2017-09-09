<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->timestamps();
        });

        Schema::create('author_book', function (Blueprint $table) {
            $table->integer('book_id')->nullable()->unsigned()->index();

            $table->foreign('book_id')
              ->references('id')
              ->on('books')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('author_id')->nullable()->unsigned()->index();

            $table->foreign('author_id')
              ->references('id')
              ->on('authors')
              ->onUpdate('cascade')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('authors');
        Schema::drop('author_book');
    }
}
