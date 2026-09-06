<?php

namespace Modules\Church\Widgets;

use Modules\Church\Abstracts\ChurchWidget;
use Modules\Church\Models\Church;
use Modules\Demarcations\Models\Demarcation;

class SubRegionSnapshot extends ChurchWidget
{
    public $default_name = 'church::widgets.sub_region_snapshot';

    public $default_settings = [
        'width' => 'col-md-6',
    ];

    public function show()
    {
        $data = $this->remember('totals', function () {
            return [
                'count' => Demarcation::subregion()->count(),
                'churches' => Church::where('enabled', 1)->count(),
                'regions' => Demarcation::region()->count(),
            ];
        });

        return $this->view('church::widgets.stats', [
            'color' => 'danger',
            'icon' => 'fa fa-map-marker',
            'value' => $data['count'],
            'left_label' => trans_choice('church::general.region', 2),
            'left_value' => $data['regions'],
            'right_label' => trans('church::widgets.churches'),
            'right_value' => $data['churches'],
            'url' => route('demarcations.subregion.index'),
        ]);
    }
}
