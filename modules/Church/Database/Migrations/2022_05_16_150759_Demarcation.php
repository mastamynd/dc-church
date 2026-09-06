<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Demarcation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('demarcations')) {
            return;
        }

        Schema::create('demarcations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('name');
            $table->integer('type');
            $table->boolean('enabled');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['type'], 'demarc_type_inx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demarcations');
    }
}
