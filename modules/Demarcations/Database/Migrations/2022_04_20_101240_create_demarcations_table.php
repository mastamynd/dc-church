<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemarcationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('demarcations', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('company_id')->default(1);
			$table->string('name');
			$table->string('type');
			$table->boolean('enabled')->default(1);
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
		Schema::dropIfExists('demarcations');
	}
}
