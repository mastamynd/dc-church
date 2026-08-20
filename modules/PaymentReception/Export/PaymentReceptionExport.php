<?php
namespace Modules\PaymentReception\Export;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class PaymentReceptionExport implements FromView
{
	/**
	* @return \Illuminate\Support\Collection
	*/
	public $data;
	public $title;

	public function __construct($data, $title)
	{
		$this->data = $data;
		$this->title = $title;
	}

	public function view(): View
	{
		$data = $this->data;
		$title = $this->title;
		return view('payment-reception::reports.export-form', compact('data', 'title'));
	}
}


