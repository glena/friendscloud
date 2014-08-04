<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTokensToTwitterHandlersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('twitter_handlers', function(Blueprint $table)
		{
            $table->string('oauth_token');
            $table->string('oauth_token_secret');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('twitter_handlers', function(Blueprint $table)
		{
            $table->dropColumn('oauth_token');
            $table->dropColumn('oauth_token_secret');
		});
	}

}
