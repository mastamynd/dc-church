<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardPerformanceIndexesToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('transactions')) {
            try {
                Schema::table('transactions', function (Blueprint $table) {
                    $table->index(['company_id', 'type', 'paid_at'], 'transactions_company_type_paid_at_index');
                });
            } catch (\Throwable $e) {
                // Index already exists on this environment.
            }
        }

        if (Schema::hasTable('documents')) {
            try {
                Schema::table('documents', function (Blueprint $table) {
                    $table->index(['company_id', 'type', 'status', 'created_at'], 'documents_company_type_status_created_index');
                });
            } catch (\Throwable $e) {
                // Index already exists on this environment.
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ([
            'transactions' => 'transactions_company_type_paid_at_index',
            'documents' => 'documents_company_type_status_created_index',
        ] as $table => $index) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            try {
                Schema::table($table, function (Blueprint $blueprint) use ($index) {
                    $blueprint->dropIndex($index);
                });
            } catch (\Throwable $e) {
                // Index may not exist on this environment.
            }
        }
    }
}
