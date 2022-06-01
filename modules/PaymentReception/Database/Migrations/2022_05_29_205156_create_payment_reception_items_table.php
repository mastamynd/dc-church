<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReceptionItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_reception_items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('payment_reception_id');
			$table->integer('year');
			$table->integer('month');
			$table->integer('week');
			$table->double('amount',10, 2)->default(0);
			$table->double('topup', 10, 2)->default(0);
			$table->softDeletes();
			$table->timestamps();
			
			$table->index(['payment_reception_id'], 'pri_pri_inx');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payment_reception_items');
	}
}
