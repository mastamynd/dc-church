<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReceptionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_receptions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('church_id');
			$table->string('payment_mode');
			$table->string('reference')->nullable();
			$table->string('phone_number')->nullable();
			$table->string('payer_name')->nullable();
			$table->string('payer_phone')->nullable();
			$table->string('payer_email')->nullable();
			$table->integer('created_by')->nullable();
			$table->string('status')->default('pending')->nullable();
			$table->integer('posted_by')->nullable();
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
		Schema::dropIfExists('payment_receptions');
	}
}
