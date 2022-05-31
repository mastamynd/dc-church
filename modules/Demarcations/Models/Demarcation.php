<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Church\Models\ChurchHistory;
use Modules\Church\Models\PastorHistory;

class Demarcation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'type','enabled'];

	protected $appends = ['churches_count', 'demarcation_count'];

    public function history(): HasMany
    {
        return $this->hasMany(DemarcationHistory::class, 'demarcation_id')->withTrashed()->orderBy('created_at', 'desc');
    }

    public function children(): HasMany{
        if($this->type == "Region"){
            return $this->hasMany(DemarcationHistory::class, 'parent_id')->orderBy('created_at', 'desc');
        }
        else{
            return $this->hasMany(ChurchHistory::class, 'demarcation_id')->orderBy('created_at', 'desc');
        }
    }

    public function scopeRegion($query){
        return $query->where('type', 'Region');
    }
    

    public function scopeSubregion($query){
        return $query->where('type', 'Sub Region');
    }

    public function parent(){
        return $this->hasOne(DemarcationHistory::class, 'demarcation_id');
    }

    public function churches($count_items = false){
		$subregion_ids = DemarcationHistory::where('parent_id', $this->id)->select('demarcation_id')->get()->pluck('demarcation_id'); 
		$churches = ChurchHistory::whereIn('demarcation_id', $subregion_ids)->get();
		
		if($count_items){
			return $churches->count();
		}

		return $churches;
	}

	public function getChurchesCountAttribute(){
		return $this->churches(true);
	}

	public function getDemarcationCountAttribute(){
		return $this->history->count() ?? 0;
	}

    public function overseer(){
        return $this->hasOne(DemarcationOverseer::class);
    }

    public function pastors(){
        return $this->hasMany(DemarcationOverseer::class)->withTrashed()->orderBy('created_at', 'asc');
    }
}
