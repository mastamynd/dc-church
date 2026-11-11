<?php

namespace Modules\Demarcations\Models;

use App\Abstracts\Model;
use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\ChurchHistory;
use Modules\Church\Models\PastorHistory;
use Modules\Church\Models\PaymentDivision;

class Demarcation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'type', 'enabled', 'bank_id', 'paybill', 'account_number', 'branch_code'];

	protected $appends = ['churches_count', 'demarcation_count', 'received', 'paid'];

    protected $paymentAmountCache = [];

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

    public function getReceivedAttribute(){
        $type = strtolower($this->type);
        $payment = PaymentDivision::where($type == 'region' ? 'regional_id' : 'sub_regional_id', $this->id)->selectRaw('SUM(amount+topup) as amount')->first();
        return $payment->amount;
    }

    public function getPaidAttribute(){
        $type = strtolower($this->type);
        $payment = PaymentDivision::where($type == 'region' ? 'regional_id' : 'sub_regional_id', $this->id)
            ->selectRaw('SUM(amount+topup) as amount')->whereNotNull('payment_date')->first();
        return $payment->amount;
    }

    public function amounts($type, $other=false){
        $cacheKey = $type . ':' . (int) (bool) $other;

        if (array_key_exists($cacheKey, $this->paymentAmountCache)) {
            return $this->paymentAmountCache[$cacheKey];
        }

        if ($other) {
            $total = 0;

            foreach ($this->pastors_fund($type) as $amnt) {
                $total += $amnt->amount;
            }

            return $this->paymentAmountCache[$cacheKey] = $total;
        }

        $query = PaymentDivision::query()
            ->where($this->paymentColumn(), $this->id)
            ->where('type', $this->paymentType());

        if ($type == 'paid') {
            $query->whereNotNull('payment_date');
        } else {
            $query->whereNull('payment_date');
        }

        return $this->paymentAmountCache[$cacheKey] = (float) $query->sum('amount');
    }

    public static function hydratePaymentTotals(Collection $demarcations): void
    {
        if ($demarcations->isEmpty()) {
            return;
        }

        $demarcations->groupBy(function ($demarcation) {
            return strtolower((string) $demarcation->type);
        })->each(function (Collection $items, $type) {
            $column = $type === 'region' ? 'regional_id' : 'sub_regional_id';
            $paymentType = $type === 'region' ? 'region' : 'sub_region';
            $ids = $items->pluck('id')->filter()->all();

            if ($ids === []) {
                return;
            }

            $totals = PaymentDivision::query()
                ->where('type', $paymentType)
                ->whereIn($column, $ids)
                ->selectRaw($column . ' as demarcation_id, SUM(CASE WHEN payment_date IS NULL THEN amount ELSE 0 END) as received, SUM(CASE WHEN payment_date IS NOT NULL THEN amount ELSE 0 END) as paid')
                ->groupBy($column)
                ->get()
                ->keyBy('demarcation_id');

            foreach ($items as $item) {
                $row = $totals->get($item->id);

                $item->setPaymentAmountCache('received', (float) ($row->received ?? 0));
                $item->setPaymentAmountCache('paid', (float) ($row->paid ?? 0));
            }
        });
    }

    public function setPaymentAmountCache($type, $value, $other=false): void
    {
        $this->paymentAmountCache[$type . ':' . (int) (bool) $other] = $value;
    }

    protected function paymentColumn(): string
    {
        return strtolower((string) $this->type) === 'region' ? 'regional_id' : 'sub_regional_id';
    }

    protected function paymentType(): string
    {
        return strtolower((string) $this->type) === 'region' ? 'region' : 'sub_region';
    }

    public function pastors_fund($type="paid"){
        $db_prefix = DB::getTablePrefix();
        $paid = PaymentDivision::join('pastors as p', 'p.id', 'payment_divisions.contact_id')->join('contacts as co', 'p.contact_id', 'co.id')
        ->join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNull('bill_id')
        ->selectRaw($db_prefix.'c.name, '.$db_prefix.'co.id as pastor_id, '.$db_prefix.'co.name as pastor, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids');
        if($this->type == "Region"){
            $paid = $paid->where('regional_id', $this->id);
        }
        else{
            $paid = $paid->where('sub_regional_id', $this->id);
        }

        $paid = $type == "paid" ? $paid->whereNotNull('payment_date') : $paid;

        $paid = $paid->where('payment_divisions.type', 'pastors_fund')->groupBy('p.id', 'c.id', 'month', 'year')->get();

        return $paid;
    }

    public function pending_payments($type="pastors_fund"){
        $db_prefix = DB::getTablePrefix();
        $paid = PaymentDivision::join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNull('bill_id')
        ->selectRaw('name, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids');
        if($this->type == "Region"){
            $paid = $paid->where('regional_id', $this->id);
        }
        else{
            $paid = $paid->where('sub_regional_id', $this->id);
        }

        $paid = !in_array($type, ["", null, false]) ? $paid->where('type', $type) : $paid;

        $paid = $paid->whereNotIn('type', ['dc_secretariate'])->groupBy('c.id', 'month', 'year')->get();

        return $paid;
    }

    public function other_pending_payments($type){
        $db_prefix = DB::getTablePrefix();
        $paid = PaymentDivision::join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNull('bill_id')
            ->selectRaw('name, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids')
            ->where('regional_id', $this->id)->where('type', $type)->first();

        return $paid;
    }

    public function paid_rows(){
        $db_prefix = DB::getTablePrefix();
        $paid = PaymentDivision::join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNotNull('payment_date')->selectRaw('name, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids');
        if($this->type == "Region"){
            $paid = $paid->where('regional_id', $this->id)->where('type', 'region');
        }
        else{
            $paid = $paid->where('sub_regional_id', $this->id)->where('type', 'sub_region');
        }

        $paid = $paid->whereNotIn('type', ['dc_secretariate'])->groupBy('month', 'year')->get();

        return $paid;
    }

    public function other_paid_rows($type){
        $db_prefix = DB::getTablePrefix();
        $paid = PaymentDivision::join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNotNull('payment_date')->selectRaw('name, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids')
            ->where('regional_id', $this->id)->where('type', $type)->first();

        return $paid;
    }

    public function received_amount_rows(){
        $db_prefix = DB::getTablePrefix();
        $received = PaymentDivision::join('churches as c', 'c.id', 'payment_divisions.church_id')->whereNull('payment_date')->selectRaw('name, SUM(amount) as amount, month, year, GROUP_CONCAT('.$db_prefix.'payment_divisions.id SEPARATOR "-") as pd_ids');
        if($this->type == "Region"){
            $received = $received->where('regional_id', $this->id)->where('type', 'region');
        }
        else{
            $received = $received->where('sub_regional_id', $this->id)->where('type', 'sub_region');
        }


        $received = $received->whereNotIn('type', ['dc_secretariate'])->groupBy('month', 'year')->get();

        return $received;
    }

    public function subregions(){
        return $this->hasMany(DemarcationHistory::class, 'parent_id')->orderBy('created_at', 'desc');
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
		return $this->children->count() ?? 0;
	}

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function overseer(){
        return $this->hasOne(DemarcationOverseer::class);
    }

    public function pastors(){
        return $this->hasMany(DemarcationOverseer::class)->withTrashed()->orderBy('created_at', 'asc');
    }
   
    public function bills(){
        return $this->hasMany(Document::class, $this->type == 'Region' ? 'regional_id' : 'sub_regional_id');
    }
}
