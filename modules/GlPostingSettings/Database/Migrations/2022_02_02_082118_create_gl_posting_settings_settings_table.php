<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlPostingSettingsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_posting_settings_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_type');
            $table->string('account_type');
            $table->string('income_ac_to_credit');
            $table->string('cash_book_ac_to_dedit');
            $table->string('debtors_acc');
            $table->boolean('status');
            $table->integer('company_id');
            $table->timestamps();
			$table->softDeletes();

            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gl_posting_settings_settings');
    }
}
