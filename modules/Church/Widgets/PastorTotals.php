<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;
use Modules\Church\Models\Pastor;

class PastorTotals extends ChurchWidget
{
    public $default_name = 'church::widgets.pastor_totals';

    public function show()
    {
        $data = $this->remember('totals', function () {
            $enabled = (int) Pastor::where('enabled', 1)->count();
            $assigned = (int) Pastor::where('enabled', 1)->whereHas('rank')->count();

            return [
                'enabled' => $enabled,
                'assigned' => $assigned,
                'unassigned' => max($enabled - $assigned, 0),
            ];
        });

        return $this->view('church::widgets.stats', [
            'color' => 'default',
            'icon' => 'fa fa-users',
            'value' => $data['enabled'],
            'left_label' => trans('church::widgets.assigned'),
            'left_value' => $data['assigned'],
            'right_label' => trans('church::widgets.unassigned'),
            'right_value' => $data['unassigned'],
            'url' => route('church.pastors.index'),
        ]);
    }
}
