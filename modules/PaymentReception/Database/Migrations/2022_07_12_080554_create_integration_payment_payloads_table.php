<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationPaymentPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integration_payment_payloads', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->longText('payload');
            $table->softDeletes();
            $table->timestamps();

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integration_payment_payloads');
    }
}
