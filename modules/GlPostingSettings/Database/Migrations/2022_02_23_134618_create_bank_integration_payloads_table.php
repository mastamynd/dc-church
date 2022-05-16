<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankIntegrationPayloadsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_integration_payloads', function (Blueprint $table) {
			$table->id();
			$table->text('payload');
			$table->string('bank');
			$table->integer('contact_id');
			$table->integer('account_id')->nullable();
			$table->integer('de_account_id')->nullable();
			$table->string('reference')->nullable();
			$table->string('mode_of_payment')->nullable();
			$table->string('status');
			$table->boolean('job_done')->default(0);
			$table->string('ip_address')->nullable();
			$table->string('payload_type')->default('transaction');
			$table->dateTime('transaction_time')->nullable();
			$table->dateTime('receipt_date')->nullable();
			$table->string('pay_load_hash', 255)->nullable();
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
		Schema::dropIfExists('bank_integration_payloads');
	}
}