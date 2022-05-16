<?php

namespace Modules\Church\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\Church;

class Main extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $churches = Church::with('pastors')->collect();
        return $this->response('church::churches.index', compact('churches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->response('church::churches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $churchAutoInc = setting('last_church_code_number');

        $churchPrefix = setting('church_code_prefix');

        if(empty($churchPrefix)){
            $churchPrefix = 'DCI';
            setting(['church_code_prefix' => $churchPrefix])->save();
        }

        if(empty($churchAutoInc)){
            $churchAutoInc = 1;
            setting(['last_church_code_number' => $churchAutoInc])->save();
        }

        $request->merge(['code' => $churchPrefix.str_pad($churchAutoInc,3,"0",STR_PAD_LEFT)]);

        $img_path = 'public/img/empty_pages/churches.png';

        if($request->hasFile('photo')){
			$image = $request->file('photo');
            $name = time()*floor(rand()*10).'.'.$image->extension();
            $image->move(public_path().'/churches/', $name);

            $img_path = 'public/churches/'.$name;
        }

        $request->merge(['photo' => $img_path]);
        $request->merge(['enabled' => 1]);

        $church = new Church();
        $church->create($request->all());

        setting(['last_church_code_number' => $churchAutoInc+1])->save();

        flash('Church was added successfully!')->success();

        return redirect()->route('church.index');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $church = Church::find($id);
        return $this->response('church::churches.show', compact('church'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $church = Church::find($id);
        return $this->response('church::churches.edit', compact('church'));
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
        $img_path = 'public/img/empty_pages/churches.png';

        if($request->hasFile('photo')){
			$image = $request->file('photo');
            $name = time()*floor(rand()*10).'.'.$image->extension();
            $image->move(public_path().'/churches/', $name);

            $img_path = 'public/churches/'.$name;
        }

        $request->merge(['photo' => $img_path]);

        // return json_encode($request->all());

        $church = Church::find($id);
        $church->update($request->all());

        flash('Church was updates successfully!')->success();

        return redirect()->route('church.show', $church->id);
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

    public function fetch_demarcations($search){

    }
}
