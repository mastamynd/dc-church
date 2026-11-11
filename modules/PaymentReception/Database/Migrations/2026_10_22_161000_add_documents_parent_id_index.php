<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDocumentsParentIdIndex extends Migration
{
	public function up()
	{
		$this->addIndex('documents', ['company_id', 'parent_id'], 'documents_company_parent_idx');
	}

	public function down()
	{
		$this->dropIndex('documents', 'documents_company_parent_idx');
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
