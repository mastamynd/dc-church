<?php

namespace Modules\PaymentReception\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Church\Models\Church;

class PaymentReception extends Model
{
	use HasFactory;
	protected $fillable = ['church_id', 'payment_mode', 'reference', 'phone_number', 'payer_name', 'payer_phone', 'payer_email', 'created_by', 'status', 'posted_by'];

	protected $appends = ['total'];

	public function items(){
		return $this->hasMany(PaymentReceptionItems::class, 'payment_reception_id')->orderBy('week', 'asc');
	}

	public function church(): BelongsTo
	{
		return $this->belongsTo(Church::class);
	}

	public function getTotalAttribute(){
		$items = $this->items;
		$total = 0;

		foreach($items as $it){
			$total += floatval($it->amount+($it->topup ?? 0));
		}
		return $total;
	}
}
