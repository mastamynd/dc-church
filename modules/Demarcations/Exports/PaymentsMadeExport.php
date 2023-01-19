<?php
namespace Modules\Demarcations\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsMadeExport implements FromView
{
	/**
	* @return \Illuminate\Support\Collection
	*/
	public $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$payments = $this->data;
		return view('demarcations::reports.payments-made-export', compact('payments'));
	}
}
