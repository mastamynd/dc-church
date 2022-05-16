<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastorsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pastors', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('company_id')->default(1);
			$table->integer('contact_id');
			$table->string('picture')->nullable()->default('images/pastor.png');
			$table->date('date_of_birth')->nullable();
			$table->string('gender');
			$table->string('id_or_passport')->nullable();
			$table->string('phone')->nullable();
			$table->string('phone2')->nullable();
			$table->string('ukumbozi_number')->nullable();
			$table->boolean('enabled')->default(1);
			$table->timestamps();

			$table->index(['contact_id'], 'pa_in_indx');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pastors');
	}
}
