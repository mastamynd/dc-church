<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;
use Modules\Church\Models\Church;
use Modules\Demarcations\Models\Demarcation;

class RegionSnapshot extends ChurchWidget
{
    public $default_name = 'church::widgets.region_snapshot';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        $data = $this->remember('totals', function () {
            return [
                'count' => Demarcation::region()->count(),
                'subregions' => Demarcation::subregion()->count(),
                'churches' => Church::where('enabled', 1)->count(),
            ];
        });

        return $this->view('church::widgets.stats', [
            'color' => 'info',
            'icon' => 'fa fa-globe',
            'value' => $data['count'],
            'left_label' => trans_choice('church::general.subregion', 2),
            'left_value' => $data['subregions'],
            'right_label' => trans('church::widgets.churches'),
            'right_value' => $data['churches'],
            'url' => route('demarcations.region.index'),
        ]);
    }
}
