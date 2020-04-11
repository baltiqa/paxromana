<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReportedListing;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Notifications\Alert;
use Carbon\Carbon;
use Talk;

class ModerateListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if(auth()->user()->permiss()[0]['report'] != null) {
            if(auth()->user()->permiss()[0]['report'] == "no") {
                abort(404);
            }
        }


        $data = [];

        $listings = ReportedListing::orderBy('created_at', 'desc');
        $data['listings'] = $listings->paginate(10);

        return view('panel::report_types.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if(auth()->user()->permiss()[0]['report'] != null) {
            if(auth()->user()->permiss()[0]['report'] == "no") {
                abort(404);
            }
        }


        $data = [];

        $listings = ReportedListing::orderBy('created_at', 'desc');
        $data['listings'] = $listings->paginate(10);

        return view('moderatelistings::admin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        dd(5);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        if(auth()->user()->permiss()[0]['report'] != null) {
            if(auth()->user()->permiss()[0]['report'] == "no") {
                abort(404);
            }
        }


        return view('moderatelistings::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {

        if(auth()->user()->permiss()[0]['report'] != null) {
            if(auth()->user()->permiss()[0]['report'] == "no") {
                abort(404);
            }
        }


        $data = [];

        $report = ReportedListing::find($id);
      
        $data['report'] = $report;
        $form = $formBuilder->create('Modules\ModerateListings\Forms\ReportForm', [
            'method' => 'PUT',
            'url' => route('panel.moderatelistings.update', $report),
            'model' => $report
        ]);
        $data['form'] = $form;

        if($report->reported_conversation != null) {
            $limit = 2000;
            $offset =0;
            Talk::setAuthUserId($report->user_id);
            $conversations = Talk::getConversationsAllById($report->reported_conversation, $offset, $limit);
            $messages = $conversations->messages;
            $data['messages'] = $messages;
        }
        

        return view('panel::report_types.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {

        if(auth()->user()->permiss()[0]['report'] != null) {
            if(auth()->user()->permiss()[0]['report'] == "no") {
                abort(404);
            }
        }



	    $report = ReportedListing::findOrFail($id);
        if($request->input('action') == 'ban_user') {
            $report->reportedUser->banned_at = Carbon::now();
            $report->reportedUser->reason_ban = "by a report from a user";
            if($report->reportedUser->trader_type == "individual") {
                    foreach($report->reportedUser->listings as $listing) {
                        $listing->is_published = 0;
                        $listing->deleted_at = Carbon::now();
                        $listing->save();
                    }
            }
            $report->reportedUser->save();

            $details = [
                'message' => "The Pax Romana Staff has watched your  report#".$report->id. " and has banned the user",
                'image' => "/web/images/icon.png",
                'url'   => "/account/report/".$report->id,
                'vendor'   => ""
             ];
    
            $report->user->notify(new Alert($details));

        }

        $details = [
            'message' => "The Pax Romana Staff has watched your report#".$report->id. " see the updates",
            'image' => "/web/images/icon.png",
            'url'   => "/account/report/".$report->id,
            'vendor'   => ""
         ];

        $report->user->notify(new Alert($details));

        $report->active = (bool) $request->input('active');
        $report->moderator_id = auth()->user()->id;
        $report->moderator_message = $request->input('moderator_message');
        $report->save();

       

        return redirect()->route('panel.moderatelistings.index')->with('message', 'The report has been updated');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
