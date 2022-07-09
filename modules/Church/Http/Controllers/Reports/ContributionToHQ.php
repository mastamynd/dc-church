<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivisionView;
use Modules\Demarcations\Models\Demarcation;

class ContributionToHQ extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function reportIndex(Request $request)
    {
        $year = !isset($request->year) ? $year = date('Y') :$request->year;
        $regional_id = !isset($request->regional_id) ? 'all' : $request->regional_id;
        $response = !isset($request->regional_id) ? Demarcation::where('type','region')->get() : Demarcation::where('id',$request->regional_id)->get();
        $regions =  Demarcation::where('type','region')->get();
        $selected_years = range(2000, date('Y'));
        
        foreach($response as $region){
            $region['churches'] = PaymentDivisionView::where('type','contribution_to_hq')->where('regional_id',$region->id)->get();
        }
        return view('church::reports.contributiontohq',compact('response','year','regional_id','regions','selected_years'));
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
}
