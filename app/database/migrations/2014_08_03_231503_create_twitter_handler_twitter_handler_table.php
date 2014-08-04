<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTwitterHandlerTwitterHandlerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitter_handler_follow', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('twitter_handler_id_from')->unsigned()->index();
			$table->foreign('twitter_handler_id_from')->references('id')->on('twitter_handlers')->onDelete('cascade');
			$table->integer('twitter_handler_id_to')->unsigned()->index();
			$table->foreign('twitter_handler_id_to')->references('id')->on('twitter_handlers')->onDelete('cascade');

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
		Schema::drop('twitter_handler_follow');
	}

}
