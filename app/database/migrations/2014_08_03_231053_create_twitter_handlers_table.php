<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTwitterHandlersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitter_handlers', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('uuid')->unique();

            $table->string('handler');
            $table->string('name');
            $table->string('location');
            $table->string('description');
            $table->string('url')->nullable();
            $table->string('image');

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
		Schema::drop('twitter_handlers');
	}

}
