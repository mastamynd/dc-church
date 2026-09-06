<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizePaymentReceptionIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addIndex('payment_receptions', ['deleted_at', 'created_at'], 'pr_deleted_created_idx');
        $this->addIndex('payment_receptions', ['church_id', 'deleted_at'], 'pr_church_deleted_idx');
        $this->addIndex('payment_receptions', ['status', 'deleted_at', 'created_at'], 'pr_status_deleted_created_idx');

        $this->addIndex('payment_reception_items', ['payment_reception_id', 'deleted_at'], 'pri_reception_deleted_idx');
        $this->addIndex('payment_reception_items', ['year', 'month', 'week', 'payment_reception_id'], 'pri_period_reception_idx');
        $this->addIndex('payment_reception_items', ['deleted_at', 'created_at'], 'pri_deleted_created_idx');

        $this->addIndex('churches', ['company_id', 'deleted_at', 'code'], 'churches_company_deleted_code_idx');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $indexes = [
            'payment_receptions' => [
                'pr_deleted_created_idx',
                'pr_church_deleted_idx',
                'pr_status_deleted_created_idx',
            ],
            'payment_reception_items' => [
                'pri_reception_deleted_idx',
                'pri_period_reception_idx',
                'pri_deleted_created_idx',
            ],
            'churches' => [
                'churches_company_deleted_code_idx',
            ],
        ];

        foreach ($indexes as $table => $names) {
            foreach ($names as $index) {
                $this->dropIndex($table, $index);
            }
        }
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
