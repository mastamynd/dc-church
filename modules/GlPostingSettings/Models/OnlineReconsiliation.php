<?php

namespace Modules\GlPostingSettings\Models;

use App\Abstracts\Model;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OnlineReconsiliation extends Model
{
	protected $table = 'bank_integration_payloads';
	protected $fillable = [];

	public function consensus_items()
	{
		return $this->hasMany(PaymentIntegrationConsensus::class, 'bank_integration_payload_id');
	}

	public function consensus_items_count(){
		return PaymentIntegrationConsensus::where('bank_integration_payload_id', $this->id)->count() ?? 0;
	}

	public function affected_invoices(){
		return PaymentIntegrationConsensus::where('bank_integration_payload_id', $this->id)->selectRaw('document_id as id, document_number as `number`')
			->groupBy('document_id')->get();
	}

	public function receipt(){
		return $this->hasOne(GlPostingReceipt::class, 'bank_integration_id');
	}

	public function account(){
		return $this->belongsTo(Account::class, 'account_id');
	}

	public function customer(){
		return $this->belongsTo(Contact::class, 'contact_id');
	}
}