<?php

namespace Modules\Church\Http\Controllers\Reports;

use App\Abstracts\Http\Controller;
use App\Notifications\Purchase\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Models\PaymentDivision;
use PhpOption\None;

class RegionalSummary extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function regionalSummaryIndex($year="current")
    {
        $takeyear = $year == "current" ? date('Y') : $year;
        $types = ['secretarait','regional','sub_regional','ukombozi'];
        $months = ['january','february','march','april','may','june','july','august','september','october','november','december'];
        $response = [];
        foreach($types as $type){
            $month_record = [];
            foreach($months as $month){
                $month_record[$month] = PaymentDivision::where('year',$year)->sum($month);
            }
            $response[$type] = $month_record;
        }
        return $this->response('church::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createdata()
    {
        return response()->json('done');
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
