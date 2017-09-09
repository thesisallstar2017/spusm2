<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id')->unsigned();

            $table->foreign('book_id')
              ->references('id')
              ->on('books')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('user_id')->unsigned();

            $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->enum('type', ['reserved', 'non-reserved']);
            $table->string('status', 50)->index();
            $table->dateTime('reserved_at')->nullable()->index();
            $table->dateTime('borrowed_at')->nullable()->index();
            $table->dateTime('return_at')->nullable()->index();
            $table->dateTime('returned_at')->nullable()->index();
            $table->boolean('is_expired')->index();
            $table->dateTime('expired_at')->nullable()->index();
            $table->boolean('is_overdue')->index();
            $table->boolean('is_lost')->index();

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
