<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeChurchListingIndexesAndView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('pastors') && !Schema::hasColumn('pastors', 'deleted_at')) {
            Schema::table('pastors', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        $this->addIndex('churches', ['company_id', 'deleted_at'], 'churches_company_deleted_idx');
        $this->addIndex('churches', ['company_id', 'deleted_at', 'name'], 'churches_company_deleted_name_idx');
        $this->addIndex('church_histories', ['church_id', 'deleted_at', 'id'], 'church_histories_church_deleted_id_idx');
        $this->addIndex('demarcation_histories', ['demarcation_id', 'deleted_at', 'id'], 'demarcation_histories_demarcation_deleted_id_idx');
        $this->addIndex('pastor_histories', ['church_id', 'rank', 'deleted_at', 'id'], 'pastor_histories_church_rank_deleted_id_idx');

        $this->recreateChurchViews();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIndex('churches', 'churches_company_deleted_idx');
        $this->dropIndex('churches', 'churches_company_deleted_name_idx');
        $this->dropIndex('church_histories', 'church_histories_church_deleted_id_idx');
        $this->dropIndex('demarcation_histories', 'demarcation_histories_demarcation_deleted_id_idx');
        $this->dropIndex('pastor_histories', 'pastor_histories_church_rank_deleted_id_idx');
    }

    protected function recreateChurchViews()
    {
        $prefix = DB::getTablePrefix();

        DB::statement('DROP VIEW IF EXISTS `' . $prefix . 'church_views`');

        DB::statement("
            CREATE SQL SECURITY INVOKER VIEW `{$prefix}church_views` AS
            SELECT
                `c`.`id` AS `id`,
                `c`.`name` AS `name`,
                `c`.`code` AS `code`,
                `co`.`id` AS `pastor_id`,
                `co`.`name` AS `pastor`,
                `p`.`id_or_passport` AS `id_or_passport`,
                `p`.`phone` AS `phone`,
                `ph`.`position` AS `position`,
                `ph`.`rank` AS `rank`,
                `sr`.`name` AS `sub_region`,
                `r`.`name` AS `region`,
                `r`.`id` AS `region_id`,
                `sr`.`id` AS `sub_region_id`,
                `c`.`deleted_at` AS `deleted_at`
            FROM `{$prefix}churches` `c`
            LEFT JOIN `{$prefix}church_histories` `ch`
                ON `ch`.`church_id` = `c`.`id` AND `ch`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}demarcation_histories` `dh`
                ON `dh`.`demarcation_id` = `ch`.`demarcation_id` AND `dh`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}demarcations` `sr`
                ON `sr`.`id` = `ch`.`demarcation_id` AND `sr`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}demarcations` `r`
                ON `r`.`id` = `dh`.`parent_id` AND `r`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}pastor_histories` `ph`
                ON `ph`.`rank` = 'Senior' AND `ph`.`church_id` = `c`.`id` AND `ph`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}pastors` `p`
                ON `p`.`id` = `ph`.`pastor_id` AND `p`.`deleted_at` IS NULL
            LEFT JOIN `{$prefix}contacts` `co`
                ON `co`.`id` = `p`.`contact_id`
            WHERE `c`.`deleted_at` IS NULL
        ");
    }

    protected function addIndex($table, array $columns, $name)
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
                $blueprint->index($columns, $name);
            });
        } catch (\Throwable $e) {
            // Index already exists on this environment.
        }
    }

    protected function dropIndex($table, $name)
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($name) {
                $blueprint->dropIndex($name);
            });
        } catch (\Throwable $e) {
            // Index may not exist on this environment.
        }
    }
}
