<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlPostingReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_posting_receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->integer('company_id')->default(1);
            $table->integer('bank_integration_id');
            $table->decimal('amount', 10, 2);
            $table->integer('ammendment')->default(1);
            $table->string('download_link')->nullable();
            $table->integer('created_by');
            $table->integer('contact_id');
            $table->string('class')->nullable();
            $table->string('mode_of_payment')->nullable();
            $table->string('currency')->nullable();
            $table->timestamps();
			$table->softDeletes();

            $table->index(['bank_integration_id'], 'gl_p_r_bii');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gl_posting_receipts');
    }
}
