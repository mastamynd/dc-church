<?php

namespace Modules\Demarcations\Http\Controllers;

use App\Abstracts\Http\Controller;
use Modules\Demarcations\Models\Demarcation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Demarcations\Models\DemarcationHistory;
use Modules\Demarcations\Models\DemarcationOverseer;

class Main extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function region_index()
	{
		$type = 'region';
		$demarcations = Demarcation::region()->collect();
		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function subregion_index(){
		$type = 'subregion';
		$demarcations = Demarcation::subregion()->collect();

		// return response()->json($demarcations, 200);

		return $this->response('demarcations::index', compact('demarcations', 'type'));
	}

	public function getNextDemarcationCode($type, $increment=false){
		$demAutoIncStr = $type == "region" ? 'region_code_number' : 'sub_region_code_number';
		$demarcationAutoInc = setting($demAutoIncStr);

		$demPrefixStr = $type == "region" ? 'DR' : 'DRS';
		$demPrefix = setting($demPrefixStr);

		if(empty($demPrefix)){
			$demPrefix = $type == "region" ? 'DR' : 'DRS';
			setting([$demPrefixStr => $demPrefix])->save();
		}

		if(empty($demarcationAutoInc)){
			$demarcationAutoInc = 1;
			setting([$demAutoIncStr => $demarcationAutoInc])->save();
		}

		$possible_next_code = $demPrefix.$demarcationAutoInc;

		if($increment){
			setting([$demAutoIncStr => $demarcationAutoInc+1])->save();
		}

		return $possible_next_code;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($type = 'region')
	{
		$possible_next_code = $this->getNextDemarcationCode($type);

		$regions = Demarcation::region()->orderBy('name', 'asc')->get();
		return view('demarcations::create', compact('type', 'regions', 'possible_next_code'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	*/
	public function store(Request $request, $type)
	{
		$possible_next_code = $this->getNextDemarcationCode($type, true);
		$request->merge(['code'=>$possible_next_code]);

		// return json_encode($request->all());

		$demarcation = Demarcation::create($request->all());

		if($type == 'subregion'){
			DemarcationHistory::create([
				'demarcation_id' => $demarcation->id,
				'parent_id' => $request->region_id
			]);
		}
		flash(ucwords($type).' has been created!')->success();
		return redirect()->back();
	}

	/**
	 * Show the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($type, $id)
	{
		$demarcation = Demarcation::find($id);
		return view('demarcations::show', compact('demarcation', 'type'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($type, $id)
	{
		$regions = Demarcation::region()->orderBy('name', 'asc')->get();
		$demarcation = Demarcation::find($id);
		$type = $demarcation->type;
		return view('demarcations::edit', compact('demarcation', 'regions', 'type'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(Request $request, $type, $id)
	{
		$demarcation = Demarcation::find($id);
		$demarcation->update($request->all());

		if($demarcation->type == "Sub Region" && ($demarcation->parent->parent_id != $request->region_id)){
			DemarcationHistory::where('demarcation_id', $demarcation->id)->delete();
			DemarcationHistory::create([
				'demarcation_id' => $demarcation->id,
				'parent_id' => $request->region_id
			]);
		}

		return $demarcation->type == "Sub Region" ? redirect()->route('demarcations.index', 'subregion') : redirect()->route('demarcations.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function set_overseer(Request $request, $id)
	{
		DemarcationOverseer::where('id', $id)->delete();
		DemarcationOverseer::create([
			'demarcation_id' => $id,
			'pastor_id' => $request->pastor_id
		]);
		flash("Overseer Updated")->success();
		return redirect()->back();
	}

	public function export(){

	}
}
