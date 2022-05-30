<?php

namespace Modules\Church\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Church\Http\Requests\CreatePastorRequest;
use Modules\Church\Models\Pastor as ModelsPastor;
use Illuminate\Support\Str;
use Modules\Church\Models\PastorHistory;
use Illuminate\Support\Facades\DB;
use Modules\Church\Http\Requests\UpdatePastorRequest;
use Modules\Church\Models\Church;

class Pastor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function indexPastors()
    {
        $pastors = ModelsPastor::with('contact')->collect();
        // return response()->json($pastors);
        return $this->response('church::pastors/index', compact('pastors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createPastor()
    {


        return view('church::pastors/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function storePastors(CreatePastorRequest $request)
    {
        if (isset($request->can_login)) {
            $user = User::create($request->all());
            $request->merge(['user_id' => $user->id]);
        }
        $img_path = 'public/img/empty_pages/customers.png';


        $contact = Contact::create($request->all());
        $request->merge(['contact_id' => $contact->id]);
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $name = time() * floor(rand() * 10) . '.' . $image->extension();
            $image->move(public_path() . '/churches/pastors/', $name);

            $img_path = 'public/churches/pastors/' . $name;
        }

        $request->merge(['picture' => $img_path]);
        $pastor = ModelsPastor::create($request->all());
        return redirect()->route('church.pastors.index');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function showPastor($id)
    {
        $pastor = ModelsPastor::find($id)->with(['contact', 'rank', 'rank_history'])->first();
        $history = PastorHistory::where('pastor_id', $id)->collect();
        // return response()->json($pastor);
        return view('church::pastors/show', compact('pastor', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function editPastor($id)
    {
        $pastor = ModelsPastor::with('contact')->where('id', $id)->first();
        return view('church::pastors/edit', compact('pastor'));
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

    public function get_pastors(Request $request, $search = '')
    {

        $db_prefix = DB::getTablePrefix();

        $pastors = ModelsPastor::join('contacts', 'contacts.id', 'pastors.contact_id')->select("pastors.id", "name");

        if (trim($search) != '') {
            $pastors = $pastors->where(function ($orW) use ($search) {
                $orW->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        return json_decode($pastors->get());
    }
    
    public function get_church(Request $request, $search = '')
    {

        $db_prefix = DB::getTablePrefix();

        $churches = Church::selectRaw('id, name, code, CONCAT(code, " - ", name) as codename');

        if (trim($search) != '') {
            $churches = $churches->where(function ($orW) use ($search) {
                $orW->where('name', 'like', '%' . $search . '%')->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        return json_decode($churches->get());
    }

    public function add_pastor_history(Request $request)
    {
        // return json_encode($request->all());

        $pastor = ModelsPastor::find($request->pastor_id);

        $previousHistory = $pastor->history;

        PastorHistory::where('pastor_id', $request->pastor_id)->where('church_id', $request->church_id)->delete();

        PastorHistory::create([
            'pastor_id' => $request->pastor_id,
            'church_id' => $request->church_id,
            'position' => $request->has('position') ? $request->position : $previousHistory->position,
            'rank' => $request->has('rank') ? $request->rank : $previousHistory->rank
        ]);

        flash('Pastor History changed')->success();
        return redirect()->back();
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

    public function updatePastor(UpdatePastorRequest $request){
        $check_pastor = ModelsPastor::find($request->pastor_id)->with("contact")->first();
        if (isset($request->can_login)) {
            if($check_pastor->contact->user_id > 0){
                $user = User::find($check_pastor->contact->user_id)->update($request->all());
                $request->merge(['user_id' => $check_pastor->contact->user_id]);
            }else{
                $user = User::create($request->all());
                $request->merge(['user_id' => $user->id]);
            }
            // return response()->json($user);
            
        }
        $img_path = 'public/img/empty_pages/customers.png';


        $contact = Contact::find($check_pastor->contact->id)->update($request->all());
        $request->merge(['contact_id' => $check_pastor->contact->id]);
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $name = time() * floor(rand() * 10) . '.' . $image->extension();
            $image->move(public_path() . '/churches/pastors/', $name);

            $img_path = 'public/churches/pastors/' . $name;
        }

        $request->merge(['picture' => $img_path]);
        $pastor = ModelsPastor::find($request->pastor_id)->update($request->all());
        return redirect()->route('church.pastors.index');
    }
}