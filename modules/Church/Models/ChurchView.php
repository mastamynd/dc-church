<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Demarcations\Models\Demarcation;

class ChurchView extends Model
{
	use HasFactory;

	protected $table = 'church_views';
}
