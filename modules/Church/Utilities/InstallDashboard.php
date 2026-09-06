<?php

namespace Modules\Church\Utilities;

use App\Models\Common\Dashboard;
use App\Models\Common\Widget;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Schema;

class InstallDashboard
{
    use Permissions;

    public const VERSION = 1;

    protected static $ran = false;

    public function ensure(): void
    {
        if (self::$ran) {
            return;
        }

        self::$ran = true;

        if (!function_exists('module') || !($module = module('church')) || (method_exists($module, 'isEnabled') && !$module->isEnabled())) {
            return;
        }

        if ((int) setting('church.dashboard_widgets_version') >= self::VERSION) {
            return;
        }

        $this->attachModuleWidgetPermissions('church');

        if (!$this->seedWidgets()) {
            return;
        }

        setting(['church.dashboard_widgets_version' => self::VERSION])->save();
    }

    public function widgetClasses(): array
    {
        return [
            'Modules\Church\Widgets\PastorTotals',
            'Modules\Church\Widgets\PastorFund',
            'Modules\Church\Widgets\PaymentsOverview',
            'Modules\Church\Widgets\RegionSnapshot',
            'Modules\Church\Widgets\SubRegionSnapshot',
            'Modules\Church\Widgets\PaymentsByType',
            'Modules\Church\Widgets\PaymentsByRegion',
            'Modules\Church\Widgets\MonthlyPayments',
            'Modules\Church\Widgets\LatestPastorFund',
            'Modules\Church\Widgets\PaymentsBySubRegion',
        ];
    }

    protected function seedWidgets(): bool
    {
        if (!Schema::hasTable('widgets') || !Schema::hasTable('dashboards')) {
            return false;
        }

        $dashboard = Dashboard::enabled()->orderBy('id')->first();

        if (!$dashboard) {
            return false;
        }

        $existingChurch = Widget::where('dashboard_id', $dashboard->id)
            ->whereIn('class', $this->widgetClasses())
            ->exists();

        if (!$existingChurch) {
            Widget::where('dashboard_id', $dashboard->id)->increment('sort', 20);
        }

        $sort = 1;

        foreach ($this->widgetClasses() as $class) {
            if (!class_exists($class)) {
                continue;
            }

            $widget = Widget::where('dashboard_id', $dashboard->id)->where('class', $class)->first();

            if ($widget) {
                $sort++;
                continue;
            }

            Widget::create([
                'company_id' => $dashboard->company_id,
                'dashboard_id' => $dashboard->id,
                'class' => $class,
                'name' => (new $class())->getDefaultName(),
                'sort' => $sort,
                'settings' => (new $class())->getDefaultSettings(),
                'created_from' => 'church::seed',
                'created_by' => user_id() ?: 1,
            ]);

            $sort++;
        }

        return true;
    }
}
