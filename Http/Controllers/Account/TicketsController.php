<?php

namespace App\Http\Controllers\Account;

use App\Rules\CaptchaCheck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tickets;
use App\Models\TicketsReply;
use App\Models\ReportedListing;
use MetaTag;
use Validator;


class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        MetaTag::set('id', "23");
        MetaTag::set('support_id', "1");
        MetaTag::set('title', __('messages.header_my_tickets1'));

        $data = [];

        $tickets = Tickets::orderBy('created_at', 'desc')->where('user_id',auth()->user()->id);
        $data['tickets'] = $tickets->paginate(30);

		return view('account.tickets.index',$data);
    }


    public function create() {
        MetaTag::set('id', "23");
        MetaTag::set('support_id', "2");
        MetaTag::set('title', __('messages.account_head_support_2'));

		return view('account.tickets.create');
    }

    public function closeTicket($id) {
        $ticket = Tickets::find($id);

        if($ticket == null) {
            abort(404);
        }

        if($ticket->user_id != auth()->user()->id) {
            abort(404);
        }

        if($ticket->status != "Closed") {
            $ticket->status = "Closed";
            $ticket->save();

            return redirect()->route('account.ticket.show',$ticket->id)->with('message',__('validation.closed_ticket'));
        }

        return redirect()->route('account.ticket.show',$ticket->id)->with('error',__('validation.already_closed'));

    }

    public function createTicket(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category' => 'required',
            'text' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $ticket = new Tickets;
        $ticket->title = $request->input('title');
        $ticket->text = $request->input('text');
        $ticket->user_id = auth()->user()->id;
        $ticket->status = "Unanswered";

        switch($request->input('category')) {
            case 1:
            $ticket->category= "Bug Bounty";
            break;
            case 2:
            $ticket->category= "Withdraw/Deposit";
            break;
            case 3:
            $ticket->category= "Account Recovery";
            break;
            case 4:
            $ticket->category= "Free Vendor Request";
            if(auth()->user()->pgp_key == null) {
                return redirect()->back()->with('error', __('You don\'t have a PGP key in your profile. Please update now the <a href="/account/change_pgp">PGP key.</a>'));
            }
            break;
            case 5:
            $ticket->category= "Other";
            break;
            case 6:
                $ticket->category= "FE Request";
            break;
            default:
            return redirect()->back()->with('error', __('validation.error'));
        }

        $ticket->save();

        return redirect()->route('account.list.ticket')->with('message',__('validation.ticket_placed'));
    }

    public function showTicket($id) {
        $ticket = Tickets::find($id);

        if($ticket == null) {
            abort(404);
        }

        if($ticket->user_id != auth()->user()->id) {
            abort(404);
        }

        MetaTag::set('id', "23");
        MetaTag::set('support_id', "1");
        MetaTag::set('title', __('messages.account_ticket_fge'));

        return view('account.tickets.show',compact('ticket'));
    }

    public function reply($id,Request $request) {
        $ticket = Tickets::find($id);

        if($ticket == null) {
            abort(404);
        }

        if($ticket->user_id != auth()->user()->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        if($ticket->status != "Closed") {
            $reply = new TicketsReply;
            $reply->user_id = auth()->user()->id;
            $reply->tickets_id = $ticket->id;
            if($ticket->status == 'Answered') {
             $ticket->status = "Unanswered";
             $ticket->save(); 
            }
            $reply->text = $request->input('text');
            $reply->adminreply = 0;
            $reply->save();
            return redirect()->route('account.ticket.show',$ticket->id)->with('message',__('validation.added_ticket_comment'));
        } else {
            return redirect()->back()->with('error', __('validation.already_closed'));
        }
       


    }

    public function reports() {
        MetaTag::set('id', "23");
        MetaTag::set('support_id', "3");
        MetaTag::set('title', __('messages.account_ticket_fges'));

        $data = [];

        $reports = ReportedListing::orderBy('created_at', 'desc')->where('user_id',auth()->user()->id);

        $data['reports'] = $reports->paginate(30);

		return view('account.reports.index',$data);
    }

    public function showReport($id) {
        MetaTag::set('id', "23");
        MetaTag::set('support_id', "3");
        MetaTag::set('title', __('messages.account_ticket_report_title'). " #".$id);

        $report = ReportedListing::find($id);

        if($report == null) {
            abort(404);
        }

        if($report->user_id != auth()->user()->id) {
            abort(404);
        }


		return view('account.reports.show',compact('report'));

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
      

    }
}