<?php

namespace Modules\GlPostingSettings\Models;

use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use Modules\DoubleEntry\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentIntegrationConsensus extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function account(){
        return $this->belongsTo(Account::class, 'payable_account');
    }

    public function invoice(){
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function document_item(){
        return $this->belongsTo(DocumentItem::class, 'document_item_id');
    }
}
