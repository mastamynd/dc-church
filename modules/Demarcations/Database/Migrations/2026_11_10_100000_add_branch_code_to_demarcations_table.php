<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchCodeToDemarcationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('demarcations')) {
            return;
        }

        Schema::table('demarcations', function (Blueprint $table) {
            if (!Schema::hasColumn('demarcations', 'branch_code')) {
                $table->string('branch_code', 20)->nullable();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('demarcations') || !Schema::hasColumn('demarcations', 'branch_code')) {
            return;
        }

        Schema::table('demarcations', function (Blueprint $table) {
            $table->dropColumn('branch_code');
        });
    }
}
