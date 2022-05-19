<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\ChurchHistory;

class DemarcationHistory extends Model
{
	use HasFactory;

	protected $fillable = ['demarcation_id', 'parent_id'];


	/**
	 * Create a new factory instance for the model.
	 *
	 * @return \Illuminate\Database\Eloquent\Factories\Factory
	 */
	public function parent(){
		return $this->hasOne(Demarcation::class, 'id', 'parent_id');
	}

	public function demarcation(){
		return $this->hasOne(Demarcation::class, 'id', 'demarcation_id');
	}
}
