<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->nullable()->unsigned()->index();

            $table->foreign('transaction_id')
              ->references('id')
              ->on('transaction')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('user_id')->nullable()->unsigned()->index();

            $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->enum('type', ['overdue', 'lost']);
            $table->timestamp('expired_at')->index()->nullable();
            $table->integer('overdue_day_counts');
            $table->integer('amount');
            $table->boolean('is_paid')->nullable();
            $table->string('receipt_no')->nullable();
            $table->boolean('with_change')->nullable();
            $table->integer('change')->nullable();
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
        Schema::drop('fees');
    }
}
