<?php
namespace Modules\Demarcations\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class DemarcationExport implements FromView
{
	/**
	* @return \Illuminate\Support\Collection
	*/
	public $data;
	public $type;

	public function __construct($data, $type)
	{
		$this->data = $data;
		$this->type = $type;
	}

	public function view(): View
	{
		$demarcations = $this->data;
		$type = $this->type;
		return view('demarcations::reports.demarcations-export', compact('demarcations', 'type'));
	}
}


