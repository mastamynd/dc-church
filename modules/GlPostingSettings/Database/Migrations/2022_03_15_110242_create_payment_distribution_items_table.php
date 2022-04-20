<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDistributionItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_distribution_items', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('company_id');
			$table->integer('bank_integration_payload_id');
			$table->integer('document_id');
			$table->integer('item_id');
			$table->decimal('amount', 10, 3)->default(0);
			$table->string('set_account')->nullable();
			$table->integer('set_account_id')->nullable();
			$table->softDeletes();
			$table->timestamps();

			$table->index(['item_id', 'document_id']);
			$table->index(['bank_integration_payload_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payment_distribution_items');
	}
}
