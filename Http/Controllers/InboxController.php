<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDirectMessage;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Message;
use Nahid\Talk\Conversations\Conversation;
use App\Http\Controllers\NotificationController;
use App\Models\ReportedListing;
use App\Rules\CaptchaCheck;
use Talk;
use Validator;
use MetaTag;
use GNUPG;


class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        MetaTag::set('title', __('messages.header_messages'));

        $inboxes = Talk::getInbox();

        return view('inbox.index', compact('inboxes'));
    }

    public function createMessageViaListing($listing)
    {
        MetaTag::set('title', __('messages.message_title'));

        return view('inbox.create')->with('listing',$listing);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store($listing,Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        
        if ($validator->fails()) {
            return redirect()->back()->with('error',__('validation.missed_some_fields'));
        }

      

        $user = User::find($listing->user->id);

        if($user->id == auth()->user()->id) {
            return redirect()->back()->with('error',__('validation.sent_message_to_your_self'));
        }

        if($user == null) {
            abort(404);
        }

        $message = $request->input('message');


        if($request->input('encrypt') != null) {
            if($user->pgp_key != null) {
                putenv("GNUPGHOME=/tmp");

                $gpg = new gnupg();
                $info = $gpg->import($user->pgp_key);
                $gpg->addencryptkey($info['fingerprint']);
                $message = $gpg->encrypt($request->input('message'));
            }
        }

        Talk::setAuthUserId(auth()->user()->id);
        Talk::sendMessageByUserId($listing->user->id, $message);
        $conversationId = Talk::isConversationExists($listing->user->id);
        $user->increment('unread_messages');


        return redirect(route('inbox.show', $conversationId));
    }

   

    public function sendMessage(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        $conversation = Conversation::find($request->get('recipient_id'));

        if($conversation == null) {
            return redirect()->back();
        }

        $conversation_user_one = $conversation->user_one;
        $conversation_user_two = $conversation->user_two;
        $conversation_user_three = $conversation->user_three;


   

        if(auth()->user()->id == $conversation_user_one) {
            if($conversation_user_three == 0) {
                $message = $request->input('message');
    
                if($request->input('encrypt') != null) {
                    if($user->pgp_key != null) {
                        putenv("GNUPGHOME=/tmp");
        
                        $gpg = new gnupg();
                        $info = $gpg->import($user->pgp_key);
                        $gpg->addencryptkey($info['fingerprint']);
                        $message = $gpg->encrypt($request->input('message'));
                    }
                }
        
                Talk::setAuthUserId(auth()->user()->id);
                Talk::sendMessage($request->get('recipient_id'), $message);
                
                 $user1 = User::find($conversation_user_two);
                 $user1->increment('unread_messages');

                 return redirect()->back();

            } else if (auth()->user()->id == $conversation_user_one && $conversation_user_three != 0 ) {
                $message = $request->input('message');

                if($request->input('encrypt') != null) {
                    if($user->pgp_key != null) {
                        putenv("GNUPGHOME=/tmp");
        
                        $gpg = new gnupg();
                        $info = $gpg->import($user->pgp_key);
                        $gpg->addencryptkey($info['fingerprint']);
                        $message = $gpg->encrypt($request->input('message'));
                    }
                }
        
        
                Talk::setAuthUserId(auth()->user()->id);
                Talk::sendMessage($request->get('recipient_id'), $message);
                
                $user1 = User::find($conversation_user_two);
                $user2 = User::find($conversation_user_three);
    
    
                 $user1->increment('unread_messages');
                 $user2->increment('unread_messages');
    
                 return redirect()->back();
            } 
        } 

        if(auth()->user()->id == $conversation_user_two) {
            if($conversation_user_three == 0) {
            $message = $request->input('message');

            if($request->input('encrypt') != null) {
                if($user->pgp_key != null) {
                    putenv("GNUPGHOME=/tmp");
    
                    $gpg = new gnupg();
                    $info = $gpg->import($user->pgp_key);
                    $gpg->addencryptkey($info['fingerprint']);
                    $message = $gpg->encrypt($request->input('message'));
                }
            }
    
    
            Talk::setAuthUserId(auth()->user()->id);
            Talk::sendMessage($request->get('recipient_id'), $message);
            
             $user1 = User::find($conversation_user_one);
             $user1->increment('unread_messages');


            return redirect()->back();
        } else if (auth()->user()->id == $conversation_user_two && $conversation_user_three != 0 ) {
            $message = $request->input('message');

            if($request->input('encrypt') != null) {
                if($user->pgp_key != null) {
                    putenv("GNUPGHOME=/tmp");
    
                    $gpg = new gnupg();
                    $info = $gpg->import($user->pgp_key);
                    $gpg->addencryptkey($info['fingerprint']);
                    $message = $gpg->encrypt($request->input('message'));
                }
            }
    
    
            Talk::setAuthUserId(auth()->user()->id);
            Talk::sendMessage($request->get('recipient_id'), $message);
            
            $user1 = User::find($conversation_user_one);
            $user2 = User::find($conversation_user_three);


             $user1->increment('unread_messages');
             $user2->increment('unread_messages');

             return redirect()->back();

        }


        }


        if ($conversation_user_three != 0 && auth()->user()->id == $conversation_user_three) {
            $message = $request->input('message');

            if($request->input('encrypt') != null) {
                if($user->pgp_key != null) {
                    putenv("GNUPGHOME=/tmp");
    
                    $gpg = new gnupg();
                    $info = $gpg->import($user->pgp_key);
                    $gpg->addencryptkey($info['fingerprint']);
                    $message = $gpg->encrypt($request->input('message'));
                }
            }
    
    
            Talk::setAuthUserId(auth()->user()->id);
            Talk::sendMessage($request->get('recipient_id'), $message);
            
            $user1 = User::find($conversation_user_one);
            $user2 = User::find($conversation_user_two);


             $user1->increment('unread_messages');
             $user2->increment('unread_messages');

             return redirect()->back();

        }

        return redirect()->back();

    }

    public function inviteUser(Request $request,$id) {

        $conversation = Conversation::find($id);

        if($conversation == null) {
            return redirect()->back();
        }

        $user = auth()->user();
        


        $conversation_user_one = $conversation->user_one;
        $conversation_user_two = $conversation->user_two;
        $conversation_user_three = $conversation->user_three;


        if($conversation_user_one != $user->id && $conversation_user_two != $user->id) {
            return redirect()->back();
        }

        
        if($conversation_user_three != 0) {
            return redirect()->back();
        }


        $invitedUser = User::where('username',$request->input('username'))->first();


        if($invitedUser == null) {
            return redirect()->back();
        }

        if($conversation->userone->username == $invitedUser->username ) {
            return redirect()->back();
        }

        if($conversation->usertwo->username == $invitedUser->username ) {
            return redirect()->back();
        }

        $notifications = new NotificationController();

        $notifications->invitedUser($invitedUser->id,$id);
        
        $conversation->user_three = $invitedUser->id;
        $conversation->save();

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {


        MetaTag::set('title', __('messages.account_ticket_conversation'));


        $inboxes = Talk::getInbox();
        $limit = 2000;
        $offset =0;
        $conversations = Talk::getConversationsAllById($id, $offset, $limit);
        if($conversations == null) {
            abort(404);
        }



        $messages = $conversations->messages;
        $recipient = $conversations->withUser;
        $inviter = "";
        $secondUser = $conversations->secondUser;
        if(isset($conversations->invitedUser)) {
        $inviter = $conversations->invitedUser;
        }
        foreach($messages as $message) {
            foreach($message as $fub) {
                if(!$fub->is_seen && auth()->user()->id != $fub->sender->id) {
                    Talk::makeSeen($fub->id);
                    auth()->user()->unread_messages = auth()->user()->unread_messages -1;
                    auth()->user()->update();
                }
            }
        }

        return view('inbox.show', compact('messages', 'recipient','inboxes','id','inviter','secondUser'));
    }

    public function report($id,Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        if ($validator->fails()) {
            return redirect('inbox/'.$id.'#report')->withErrors($validator)
                ->withInput();
        }

        ReportedListing::create([
            'reason' => $request->input('reason'),
            'notes' => $request->input('notes'),
            'user_id' => auth()->user()->id,
            'reported_user' => 0,
            'reported_conversation' => $id,
        ]);

        return redirect('inbox/'.$id. '#report')->with('message',__('validation.report_received'));
    
    }
}
