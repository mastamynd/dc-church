<?php

namespace Modules\Demarcations\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Common\Import as ImportRequest;
use Modules\Demarcations\Exports\BanksExport;
use Modules\Demarcations\Http\Requests\Bank as Request;
use Modules\Demarcations\Imports\Banks as Import;
use Modules\Demarcations\Models\Bank;

class Banks extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $banks = Bank::collect();

        return $this->response('demarcations::banks.index', compact('banks'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  Bank  $bank
     * @return Response
     */
    public function show(Bank $bank)
    {
        return redirect()->route('demarcations.banks.edit', $bank->id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('demarcations::banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $bank = Bank::create($request->input());

        $message = trans('messages.success.added', ['type' => trans_choice('demarcations::general.bank', 1)]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('demarcations.banks.index'),
            'data' => $bank,
            'message' => $message,
        ]);
    }

    /**
     * Import the specified resource.
     *
     * @param  ImportRequest  $request
     * @return Response
     */
    public function import(ImportRequest $request)
    {
        $response = $this->importExcel(new Import, $request, trans_choice('demarcations::general.bank', 2));

        if ($response['success']) {
            $response['redirect'] = route('demarcations.banks.index');

            flash($response['message'])->success();
        } else {
            $response['redirect'] = route('import.create', ['demarcations', 'banks']);

            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Bank  $bank
     * @return Response
     */
    public function edit(Bank $bank)
    {
        return view('demarcations::banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Bank  $bank
     * @param  Request  $request
     * @return Response
     */
    public function update(Bank $bank, Request $request)
    {
        $bank->update($request->input());

        $message = trans('messages.success.updated', ['type' => $bank->name]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('demarcations.banks.index'),
            'data' => $bank,
            'message' => $message,
        ]);
    }

    /**
     * Enable the specified resource.
     *
     * @param  Bank  $bank
     * @return Response
     */
    public function enable(Bank $bank)
    {
        $bank->update(['enabled' => 1]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $bank,
            'message' => trans('messages.success.enabled', ['type' => $bank->name]),
        ]);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Bank  $bank
     * @return Response
     */
    public function disable(Bank $bank)
    {
        $bank->update(['enabled' => 0]);

        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $bank,
            'message' => trans('messages.success.disabled', ['type' => $bank->name]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Bank  $bank
     * @return Response
     */
    public function destroy(Bank $bank)
    {
        if ($bank->demarcations()->exists()) {
            $message = trans('demarcations::general.bank_in_use');

            flash($message)->error()->important();

            return response()->json([
                'success' => false,
                'error' => true,
                'redirect' => route('demarcations.banks.index'),
                'data' => $bank,
                'message' => $message,
            ]);
        }

        $bank->delete();

        $message = trans('messages.success.deleted', ['type' => $bank->name]);

        flash($message)->success();

        return response()->json([
            'success' => true,
            'error' => false,
            'redirect' => route('demarcations.banks.index'),
            'data' => $bank,
            'message' => $message,
        ]);
    }

    /**
     * Export the specified resource.
     *
     * @return mixed
     */
    public function export()
    {
        return $this->exportExcel(new BanksExport, trans_choice('demarcations::general.bank', 2));
    }
}
