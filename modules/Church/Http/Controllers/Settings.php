<?php

namespace Modules\Church\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Settings extends Controller
{
	
	public $types = [
		'church_code_prefix', 'last_church_code_number', 'region_prefix', 'region_code_number', 'subregion_prefix', 'subregion_code_number'
	];

	public $names, $keys = [];

	public $module_name = 'church';
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */

	public function __construct()
	{
		foreach($this->types as $typ){
			$this->names[$typ] = trans($this->module_name.'::general.'.$typ);
			$key = $this->module_name.'.'.$typ;
			$this->keys[$typ] = $key;
		}
	}

	public function edit()
	{
		$types = $this->types;
		$names = $this->names;
		$keys = $this->keys;

		$divisions = setting('church.payment_divisions');
		$divisions = json_decode($divisions);

		// return response()->json($divisions, 200);

		return $this->response('church::settings.edit', compact('types', 'names', 'keys', 'divisions'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param int $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		foreach($this->types as $typ){
			setting()->set($this->module_name.'.'.$typ, $request->$typ);
		}

		setting()->set('church.payment_divisions', json_encode($request->payment_divisions));
		setting()->set('demarcations.eft_debit_account', trim((string) $request->input('eft_debit_account', '')));
		setting()->set('demarcations.eft_purpose_code', trim((string) $request->input('eft_purpose_code', '')));

		setting()->save();

		return redirect()->route('settings.index');
	}
}
