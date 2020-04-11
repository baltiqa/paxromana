<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tickets;
use App\Models\TicketsReply;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Carbon\Carbon;
use App\Notifications\Alert;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [];

        $tickets = Tickets::orderBy('created_at', 'desc');
        $data['tickets'] = $tickets->paginate(30);

        return view('panel::tickets.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $data = [];

        $ticket = Tickets::find($id);
       
        $data['ticket'] = $ticket;
        $form = $formBuilder->create('Modules\Panel\Forms\TicketForm', [
            'method' => 'PUT',
            'url' => route('panel.tickets.update', $ticket),
            'model' => $ticket
        ]);
        $data['form'] = $form;
        

        return view('panel::tickets.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {

        $ticket = Tickets::findOrFail($id);
        
        if($ticket->status != "Closed") {
            $reply = new TicketsReply;
            $reply->user_id = 0;
            $reply->tickets_id = $ticket->id;
            if($ticket->status == 'Unanswered') {
             $ticket->status = "Answered";
             $ticket->save(); 
            }
            $reply->text = $request->input('message');
            $reply->adminreply = auth()->user()->id;
            $reply->save();
            
            $details = [
                'message' => "The Pax Romana Staff has responded to your ticket #".$ticket->id,
                'image' => "/web/images/icon.png",
                'url'   => "/account/ticket/".$ticket->id,
                'vendor'   => ""
             ];
    
            $ticket->user->notify(new Alert($details));


            return redirect()->back()->with('message', 'The comment has been added');
        } else {
            return redirect()->back()->with('message','This ticket is already closed.');
        }
    }

    public function closeTicket($id) {
        $ticket = Tickets::find($id);

        if($ticket == null) {
            abort(404);
        }

        if($ticket->status != "Closed") {
            $ticket->status = "Closed";
            $ticket->closed_by = auth()->user()->id;
            $ticket->save();

            $details = [
                'message' => "The Pax Romana Staff has closed your ticket #".$ticket->id,
                'image' => "/web/images/icon.png",
                'url'   => "/account/ticket/".$ticket->id,
                'vendor'   => ""
             ];
    
            $ticket->user->notify(new Alert($details));


            return redirect()->back()->with('message','You have now closed the ticket.');
        }

        return redirect()->back()->with('error','This ticket has already been closed.');

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
