<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizePaymentsMadeIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $payments = $this->paymentTable();

        if ($payments) {
            $this->addIndex($payments, ['type', 'regional_id', 'deleted_at', 'payment_date', 'amount'], 'pd_type_region_deleted_paydate_idx');
            $this->addIndex($payments, ['type', 'sub_regional_id', 'deleted_at', 'payment_date', 'amount'], 'pd_type_subregion_deleted_paydate_idx');
            $this->addIndex($payments, ['regional_id', 'payment_date', 'type', 'cheque_number'], 'pd_region_paydate_type_cheque_idx');
            $this->addIndex($payments, ['sub_regional_id', 'payment_date', 'type', 'cheque_number'], 'pd_subregion_paydate_type_cheque_idx');
            $this->addIndex($payments, ['cheque_number', 'type', 'deleted_at'], 'pd_cheque_type_deleted_idx');

            foreach (['payment_date_2', 'payment_date_3'] as $duplicate) {
                $this->dropIndex($payments, $duplicate);
            }
        }

        $this->addIndex('demarcations', ['company_id', 'type', 'deleted_at', 'name'], 'demarcations_company_type_deleted_name_idx');
        $this->addIndex('demarcation_overseers', ['demarcation_id', 'deleted_at'], 'deov_demarcation_deleted_idx');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $payments = $this->paymentTable();

        $indexes = [
            $payments => [
                'pd_type_region_deleted_paydate_idx',
                'pd_type_subregion_deleted_paydate_idx',
                'pd_region_paydate_type_cheque_idx',
                'pd_subregion_paydate_type_cheque_idx',
                'pd_cheque_type_deleted_idx',
            ],
            'demarcations' => ['demarcations_company_type_deleted_name_idx'],
            'demarcation_overseers' => ['deov_demarcation_deleted_idx'],
        ];

        foreach ($indexes as $table => $names) {
            if (!$table) {
                continue;
            }

            foreach ($names as $index) {
                $this->dropIndex($table, $index);
            }
        }
    }

    protected function paymentTable(): ?string
    {
        if (Schema::hasTable('payment_divisions')) {
            return 'payment_divisions';
        }

        if (Schema::hasTable('payment_division')) {
            return 'payment_division';
        }

        return null;
    }

    protected function addIndex($table, array $columns, $name)
    {
        if (!Schema::hasTable($table) || $this->indexExists($table, $name)) {
            return;
        }

        $available = array_values(array_filter($columns, function ($column) use ($table) {
            return Schema::hasColumn($table, $column);
        }));

        if (count($available) !== count($columns)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($available, $name) {
                $blueprint->index($available, $name);
            });
        } catch (\Throwable $e) {
            // Index already exists on this environment.
        }
    }

    protected function dropIndex($table, $name)
    {
        if (!Schema::hasTable($table) || !$this->indexExists($table, $name)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($name) {
                $blueprint->dropIndex($name);
            });
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE `' . DB::getTablePrefix() . $table . '` DROP INDEX `' . $name . '`');
            } catch (\Throwable $ignored) {
                // Index may not exist on this environment.
            }
        }
    }

    protected function indexExists($table, $name): bool
    {
        $rows = DB::select('SHOW INDEX FROM `' . DB::getTablePrefix() . $table . '` WHERE Key_name = ?', [$name]);

        return !empty($rows);
    }
}
