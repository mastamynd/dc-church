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
		return $this->belongsTo(Contact::class, 'contact_id');
	}

	public function history(){
		return PastorHistory::where('pastor_id',$this->id)->orderBy('created_at', 'desc')->first();
	}

	public function rank(){
		return $this->hasOne(PastorHistory::class);
	}

	public function rank_history(){
		return $this->hasMany(PastorHistory::class)->withTrashed()->orderBy('created_at', 'desc');
	}

	public function getContact(){
		return Contact::find($this->contact_id);
	}

	public function getRank(){
		return PastorHistory::where('pastor_id',$this->id)->first();
	}
	public function getRankHistory(){
		return PastorHistory::withTrashed()->orderBy('created_at', 'desc');
	}
	
}
