<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->timestamps();
        });

        Schema::create('book_subject', function (Blueprint $table) {
            $table->integer('book_id')->nullable()->unsigned()->index();
            $table->foreign('book_id')
              ->references('id')
              ->on('books')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('subject_id')->nullable()->unsigned()->index();

            $table->foreign('subject_id')
              ->references('id')
              ->on('subjects')
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
        Schema::drop('subjects');
        Schema::drop('book_subject');
    }
}
