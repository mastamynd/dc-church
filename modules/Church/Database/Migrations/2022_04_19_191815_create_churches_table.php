<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChurchesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('churches', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('company_id')->default(1);
			$table->string('name');
			$table->string('code');
			$table->date('founded_on');
			$table->string('photo')->nullable()->default('images/church.png');
			$table->boolean('latitude')->nullable();
			$table->boolean('longitude')->nullable();
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
		Schema::dropIfExists('churches');
	}
}
