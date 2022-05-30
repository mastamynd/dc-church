<?php

namespace Modules\Church\Http\Controllers;

use App\Abstracts\Http\Controller;
use DemarcationHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\ChurchHistory;
use Modules\Demarcations\Models\Demarcation as ModelsDemarcation;

class Demarcation extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$demarcations = ModelsDemarcation::with('parent')->collect();
		return $this->response('church::index', compact('demarcations'));
	}

	public function assign(Request $request, $id){
		ChurchHistory::where('church_id', $id)->delete();
		ChurchHistory::create([
		   'church_id' => $id,
		   'demarcation_id' => $request->subregion_id
		]);

		flash('Church assigned to Sub Region.')->success();
		return redirect()->back();
	}

	public function fetch_demarcations(Request $request, $search = '')
	{
		$db_prefix = DB::getTablePrefix();
		$subregions = ModelsDemarcation::subregion();
		if (trim($search) != '') {
			$subregions = $subregions->where(function ($orW) use ($search) {
				$orW->where('name', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%');
			});
		}
		return json_decode($subregions->get());
	}
}
