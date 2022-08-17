<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use App\Models\Common\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pastor extends Model
{
	use HasFactory;

	protected $fillable = ['company_id','contact_id','date_of_birth','gender','id_or_passport','phone','phone2','ukumbozi_number','enabled','picture'];

	public function church()
	{
		return $this->belongsTo(PastorHistory::class, 'pastor_id');
	}

	public function contact(){
		return $this->belongsTo(Contact::class);
	}

	public function history(){
		return $this->hasOne(PastorHistory::class)->orderBy('created_at', 'desc');
	}

	public function rank(){
		return $this->hasOne(PastorHistory::class);
	}

	public function rank_history(){
		return $this->hasMany(PastorHistory::class)->withTrashed()->orderBy('created_at', 'desc');
	}

	public function payment_division(){
		return $this->hasMany(PaymentDivision::class, 'contact_id')->orderBy('created_at', 'desc');
	}
}
