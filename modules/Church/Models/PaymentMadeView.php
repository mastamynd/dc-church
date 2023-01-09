<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Demarcations\Models\Demarcation;

class PaymentMadeView extends Model
{
	use HasFactory;

	protected $table = 'payment_made_view';
}
