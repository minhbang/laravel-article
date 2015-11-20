<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('articles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 255);
			$table->string('slug', 255);
			$table->text('summary');
			$table->longText('content');
			$table->smallInteger('status')->default(0);
			$table->integer('hit')->unsigned()->default(0);
			$table->integer('user_id')->unsigned();
			$table->integer('category_id')->unsigned();
			$table->string('featured_image', 255)->nullable();
			$table->timestamp('published_at');
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
		Schema::drop('articles');
	}

}
