<?php

namespace Modules\Church\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentDivision extends Model
{
	protected $fillable = ['church_id', 'regional_id', 'sub_regional_id', 'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december', 'contact_id', 'transaction_id', 'type', 'amount', 'invoice_id', 'invoice_item_id', 'bill_id', 'bill_item_id', 'payment_date', 'year'];
    protected $table = "payment_divisions";
}
