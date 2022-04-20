<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChurchTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('church_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('church_id');
            $table->integer('transaction_id');
            $table->string('captured_from')->default('system::ui');
            $table->integer('captured_by');
            $table->integer('week');
            $table->string('month');
            $table->integer('year');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['church_id', 'transaction_id'], 'ch_tr_inx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('church_transactions');
    }
}
