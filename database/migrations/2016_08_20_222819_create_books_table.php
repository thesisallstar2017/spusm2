<?php

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
            $table->string('title')->index();
            $table->string('barcode', 50)->index();
            $table->string('publisher', 50)->index();
            $table->integer('published_year');
            $table->string('card_number', 50);
            $table->string('call_number', 50)->index();
            $table->integer('quantity')->unsigned();
            $table->integer('available_quantity')->unsigned()->nullable();
            $table->integer('material_id')->unsigned();

            $table->foreign('material_id')
              ->references('id')
              ->on('materials')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->string('isbn', 100)->nullable();
            $table->text('etal')->nullable();
            $table->string('edition', 50)->nullable();
            $table->string('publish_place')->nullable();
            $table->string('physical_desc')->nullable();
            $table->string('aetitle', 50)->nullable();
            $table->string('stitle', 50)->nullable();
            $table->text('note')->nullable();
            $table->string('book_level', 50)->nullable();
            $table->string('editor')->nullable();
            $table->string('illustrator')->nullable();
            $table->string('compiler')->nullable();
            $table->string('image_source')->nullable();

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
        Schema::drop('books');
    }
}
