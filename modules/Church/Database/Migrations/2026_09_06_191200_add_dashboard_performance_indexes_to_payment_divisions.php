<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashboardPerformanceIndexesToPaymentDivisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = Schema::hasTable('payment_divisions') ? 'payment_divisions' : (Schema::hasTable('payment_division') ? 'payment_division' : null);

        if ($table) {
            $indexes = [
                'pd_year_type_idx' => ['year', 'type'],
                'pd_year_type_payment_date_idx' => ['year', 'type', 'payment_date'],
                'pd_region_year_type_idx' => ['regional_id', 'year', 'type'],
                'pd_subregion_year_type_idx' => ['sub_regional_id', 'year', 'type'],
            ];

            foreach ($indexes as $name => $columns) {
                try {
                    Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
                        $blueprint->index($columns, $name);
                    });
                } catch (\Throwable $e) {
                    // Index already exists or a column is missing on this environment.
                }
            }
        }

        if (Schema::hasTable('church_histories')) {
            try {
                Schema::table('church_histories', function (Blueprint $blueprint) {
                    $blueprint->index(['demarcation_id', 'church_id'], 'ch_hi_demarcation_church_idx');
                });
            } catch (\Throwable $e) {
                // Index already exists on this environment.
            }
        }

        if (Schema::hasTable('pastor_histories')) {
            try {
                Schema::table('pastor_histories', function (Blueprint $blueprint) {
                    $blueprint->index(['pastor_id'], 'pastor_histories_pastor_id_index');
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
        $table = Schema::hasTable('payment_divisions') ? 'payment_divisions' : (Schema::hasTable('payment_division') ? 'payment_division' : null);

        $indexes = [
            $table => ['pd_year_type_idx', 'pd_year_type_payment_date_idx', 'pd_region_year_type_idx', 'pd_subregion_year_type_idx'],
            'church_histories' => ['ch_hi_demarcation_church_idx'],
            'pastor_histories' => ['pastor_histories_pastor_id_index'],
        ];

        foreach ($indexes as $tableName => $names) {
            if (!$tableName || !Schema::hasTable($tableName)) {
                continue;
            }

            foreach ($names as $index) {
                try {
                    Schema::table($tableName, function (Blueprint $blueprint) use ($index) {
                        $blueprint->dropIndex($index);
                    });
                } catch (\Throwable $e) {
                    // Index may not exist on this environment.
                }
            }
        }
    }
}
