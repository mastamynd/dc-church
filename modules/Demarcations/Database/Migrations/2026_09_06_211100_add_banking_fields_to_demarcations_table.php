<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankingFieldsToDemarcationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('demarcations')) {
            return;
        }

        Schema::table('demarcations', function (Blueprint $table) {
            if (!Schema::hasColumn('demarcations', 'bank_id')) {
                $table->unsignedBigInteger('bank_id')->nullable();
            }

            if (!Schema::hasColumn('demarcations', 'paybill')) {
                $table->string('paybill')->nullable();
            }

            if (!Schema::hasColumn('demarcations', 'account_number')) {
                $table->string('account_number')->nullable();
            }
        });

        Schema::table('demarcations', function (Blueprint $table) {
            if (Schema::hasColumn('demarcations', 'bank_id') && Schema::hasTable('banks')) {
                $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
            }

            if (Schema::hasColumn('demarcations', 'bank_id')) {
                $table->index(['company_id', 'bank_id'], 'demarcations_company_bank_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('demarcations')) {
            return;
        }

        Schema::table('demarcations', function (Blueprint $table) {
            if (Schema::hasColumn('demarcations', 'bank_id')) {
                $table->dropForeign(['bank_id']);
                $table->dropIndex('demarcations_company_bank_idx');
            }

            $columns = collect(['bank_id', 'paybill', 'account_number'])
                ->filter(function ($column) {
                    return Schema::hasColumn('demarcations', $column);
                })
                ->values()
                ->all();

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
}
