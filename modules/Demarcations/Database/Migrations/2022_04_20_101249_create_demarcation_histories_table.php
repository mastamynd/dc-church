<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemarcationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demarcation_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('demarcation_id');
            $table->integer('parent_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['parent_id'], 'dhi_pa_inx');
            $table->index(['demarcation_id'], 'dhi_de_inx');
            $table->index(['parent_id', 'demarcation_id'], 'dhi_pa_de_inx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demarcation_histories');
    }
}
