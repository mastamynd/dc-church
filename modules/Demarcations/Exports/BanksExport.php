<?php

namespace Modules\Demarcations\Exports;

use App\Abstracts\Export;
use Modules\Demarcations\Models\Bank as Model;

class BanksExport extends Export
{
    public function collection()
    {
        return Model::collectForExport($this->ids);
    }

    public function fields(): array
    {
        return [
            'name',
            'code',
            'enabled',
        ];
    }
}
