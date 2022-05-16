<?php

namespace Modules\GlPostingSettings\Models;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShearTransaction extends Model
{
	protected $table = 'shear_transactions';
	protected $fillable = [];

	public function getRowBalance($customer = false){
		$allPreviousRows = ShearTransaction::where('id', '>=', $this->id)->selectRaw('(SUM(invoice_total) - SUM(amount_paid)) as balance');
	
		if($customer){
			$allPreviousRows = $allPreviousRows->where('account_no', $customer);
		}

		$allPreviousRows = $allPreviousRows->first();

		return $this->id ?? 0;
	}
}