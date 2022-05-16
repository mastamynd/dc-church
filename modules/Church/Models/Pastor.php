<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pastor extends Model
{
	use HasFactory;

	protected $fillable = [];

	public function church()
	{
		return $this->belongsTo(Church::class);
	}

	public function rank(){
		return $this->hasOne(PastorHistory::class);
	}

	public function rank_history(){
		return $this->hasMany(PastorHistory::class)->withTrashed()->orderBy('created_at', 'desc');
	}
}
