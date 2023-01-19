<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Demarcation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class PaymentDivision extends Model
{
	protected $fillable = ['church_id', 'regional_id', 'sub_regional_id', 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'contact_id', 'transaction_id', 'type', 'amount', 'invoice_id', 'invoice_item_id', 'bill_id', 'bill_item_id', 'payment_date', 'month', 'year'];
	// protected $table = "view_payment_division_by_type";

	public function bill(){
		return $this->hasOne(Document::class, 'id', 'bill_id');
	}

	public function region(){
		return $this->belongsTo(ModelsDemarcation::class, 'regional_id');
	}

	public function subregion(){
		return $this->belongsTo(ModelsDemarcation::class, 'sub_regional_id')->orderBy('name');
	}

	public function invoice(){
		return $this->hasOne(Document::class, 'id', 'invoice_id');
	}

	public function church(){
		return $this->hasOne(Church::class, 'id', 'church_id')->orderBy('name');
	}

	public function pastor(){
		return $this->hasOne(Pastor::class, 'id', 'contact_id');
	}
}
