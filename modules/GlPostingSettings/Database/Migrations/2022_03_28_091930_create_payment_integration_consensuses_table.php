<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentIntegrationConsensusesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_integration_consensuses', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('bank_integration_payload_id');
			$table->string('document_type');
			$table->string('document_number');
			$table->integer('document_id')->nullable();
			$table->integer('document_item_id')->nullable();
			$table->integer('item_id');
			$table->string('item_name')->nullable();
			$table->integer('contact_id');
			$table->decimal('invoice_amount', 16, 2)->default(0);
			$table->decimal('invoice_amount_tax', 16, 2)->default(0);
			$table->decimal('paid_amount', 16, 2)->default(0);
			$table->decimal('payable_amount', 16, 2)->default(0);
			$table->boolean('fulfilment_status')->default(0);
			$table->integer('payment_id')->nullable();
			$table->integer('payable_account')->nullable();
			$table->softDeletes();
			$table->timestamps();

			$table->index(['bank_integration_payload_id'], 'pic_bip_id');
			$table->index(['contact_id', 'document_id'], 'pic_c_id_d_id');
			$table->index(['item_id', 'document_id'], 'pic_i_id_d_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('payment_integration_consensuses');
	}
}
