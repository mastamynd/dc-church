<?php

namespace Modules\Church\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Church\Models\Pastor as ModelsPastor;
use Modules\Church\Models\PastorHistory;

class Pastor extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->response('church::index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('church::create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Show the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('church::show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('church::edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	public function get_pastors(Request $request, $search=''){

		$db_prefix = DB::getTablePrefix();

		$pastors = ModelsPastor::join('contacts', 'contacts.id', 'pastors.contact_id')->select("pastors.id", "name");

		if(trim($search) != ''){
			$pastors = $pastors->where(function($orW) use($search){
				$orW->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%');
			});
		}

		return json_decode($pastors->get());
	}

	public function add_to_church(Request $request, $id){
		$pastor = ModelsPastor::find($request->pastor_id);

		$previousHistory = $pastor->history;

		PastorHistory::where('pastor_id', $request->pastor_id)->where('church_id', $id)->delete();

		PastorHistory::create([
			'pastor_id' => $request->pastor_id,
			'church_id' => $id,
			'position' => $request->has('position') ? $request->position : $previousHistory->position,
			'rank' => $request->has('rank') ? $request->rank : $previousHistory->rank
		]);

		flash('Add Pastor to Church')->success();
		return redirect()->back();
	}
}
