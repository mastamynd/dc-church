<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_division', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id')->nullable();
            $table->integer('regional_id')->nullable();
            $table->integer('sub_regional_id')->nullable();
            $table->double('january')->default(0);
            $table->double('february')->default(0);
            $table->double('march')->default(0);
            $table->double('april')->default(0);
            $table->double('may')->default(0);
            $table->double('june')->default(0);
            $table->double('july')->default(0);
            $table->double('august')->default(0);
            $table->double('september')->default(0);
            $table->double('october')->default(0);
            $table->double('november')->default(0);
            $table->double('december')->default(0);
            $table->integer('contact_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('type')->nullable();
            $table->double('amount')->default(0);
            $table->integer('invoice_id')->nullable();
            $table->integer('invoice_item_id')->nullable();
            $table->integer('bill_id')->nullable();
            $table->integer('bill_item_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('year')->nullable();
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
        Schema::dropIfExists('payment_division');
    }
}
