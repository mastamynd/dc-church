<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DemarcationHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('demarcation_histories');
        Schema::create('demarcation_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('demarcation_id');
            $table->integer('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['demarcation_id', 'parent_id'], 'demarc_parent_id_inx');
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
