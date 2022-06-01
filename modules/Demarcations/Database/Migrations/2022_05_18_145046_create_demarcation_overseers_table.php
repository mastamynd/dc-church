<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemarcationOverseersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demarcation_overseers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pastor_id');
            $table->integer('demarcation_id');
			$table->softDeletes();
            $table->timestamps();

            $table->index(['pastor_id'], 'deov_pa_inx');
            $table->index(['demarcation_id'], 'deov_de_inx');
            $table->index(['pastor_id', 'demarcation_id'], 'deov_pa_de_inx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demarcation_overseers');
    }
}
