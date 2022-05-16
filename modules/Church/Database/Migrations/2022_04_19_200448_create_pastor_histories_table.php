<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastorHistoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pastor_histories', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('pastor_id');
			$table->integer('church_id');
			$table->string('position');
			$table->string('rank');
			$table->softDeletes();
			$table->timestamps();
			

			$table->index(['church_id', 'pastor_id'], 'pa_hi_in_indx');
			$table->index(['position'], 'pa_po_in_indx');
			$table->index(['rank'], 'pa_ra_in_indx');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pastor_histories');
	}
}
