<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChurchHistoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('church_histories', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('church_id');
			$table->integer('demarcation_id');
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			$table->softDeletes();
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
		Schema::dropIfExists('church_histories');
	}
}
