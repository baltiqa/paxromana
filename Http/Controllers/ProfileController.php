<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Models\Listing;
use App\Models\User;
use App\Models\Comment;
use App\Models\ReportedListing;
use App\Rules\CaptchaCheck;
use App\Models\UserBlock;

use Talk;
use MetaTag;
use DB;
use Validator;
use GNUPG;



class ProfileController extends Controller
{

    protected $category_id;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($user, Request $request)
    {

	
        $data = [];
        $data['profile'] = $user;

        MetaTag::set('title', $user->display_name != null ? $user->display_name : $user->username);
        MetaTag::set('sub_id', 1);

        return view('profile.show', $data);
    }

    public function storeShop($user, Request $request)
    {
        if($user->trader_type != "individual" ) {
            abort(404);
        }

        $data = [];
        $data['listings'] = $user->listings()->orderBy('views_count', 'DESC')->active()->paginate(12);
        $data['profile'] = $user;


        

        MetaTag::set('title', __('messages.notification_title'). " ". $user->username);
        MetaTag::set('sub_id', 2);

        return view('profile.store', $data);
    }

    public function reviews($user, Request $request)
    {
        if($user->trader_type != "individual" || $user->is_admin == 1 ) {
            abort(404);
        }

        $data = [];
        $data['profile'] = $user;
        $data['comments'] = $user->comments()->with('commenter')->orderBy('created_at','DESC')->paginate(20);


       $month1=  DB::select( DB::raw("select round(avg(rate),1) as average from comments where created_at >= DATE(NOW() - INTERVAL 1 MONTH) and seller_id=".$user->id));
       $month3=  DB::select( DB::raw("select round(avg(rate),1) as average from comments where created_at >= DATE(NOW() - INTERVAL 3 MONTH) and seller_id=".$user->id));
       $month3older=  DB::select( DB::raw("select round(avg(rate),1) as average from comments where created_at < DATE(NOW() - INTERVAL 3 MONTH) and seller_id=".$user->id));

       $data['month1'] = $month1[0]->average;
       $data['month3'] = $month3[0]->average;
       $data['month3older'] = $month3older[0]->average;

      
       $month1count = Comment::selectRaw('(SELECT COUNT(*) FROM comments WHERE rate = 1 and created_at >= DATE(NOW() - INTERVAL 1 MONTH) and seller_id= '.$user->id.') AS rate1, (SELECT COUNT(*) FROM comments WHERE rate = 2  and  created_at >= DATE(NOW() - INTERVAL 1 MONTH) and seller_id= '.$user->id.') AS rate2, (SELECT COUNT(*) FROM comments WHERE rate = 3  and  created_at >= DATE(NOW() - INTERVAL 1 MONTH) and seller_id= '.$user->id.') AS rate3, (SELECT COUNT(*) FROM comments WHERE rate = 4  and  created_at >= DATE(NOW() - INTERVAL 1 MONTH) and seller_id= '.$user->id.') AS rate4, (SELECT COUNT(*) FROM comments WHERE rate = 5  and  created_at >= DATE(NOW() - INTERVAL 1 MONTH)  and seller_id= '.$user->id.') AS rate5')
       ->get()->first();

       $month2count = Comment::selectRaw('(SELECT COUNT(*) FROM comments WHERE rate = 1 and created_at >= DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate1, (SELECT COUNT(*) FROM comments WHERE rate = 2  and  created_at >= DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate2, (SELECT COUNT(*) FROM comments WHERE rate = 3  and  created_at >= DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate3, (SELECT COUNT(*) FROM comments WHERE rate = 4  and  created_at >= DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate4, (SELECT COUNT(*) FROM comments WHERE rate = 5  and  created_at >= DATE(NOW() - INTERVAL 3 MONTH)  and seller_id= '.$user->id.') AS rate5')
       ->get()->first();

       $month3count = Comment::selectRaw('(SELECT COUNT(*) FROM comments WHERE rate = 1 and seller_id= '.$user->id.' and created_at < DATE(NOW() - INTERVAL 3 MONTH)) AS rate1, (SELECT COUNT(*) FROM comments WHERE rate = 2  and  created_at < DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate2, (SELECT COUNT(*) FROM comments WHERE rate = 3  and  created_at < DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate3, (SELECT COUNT(*) FROM comments WHERE rate = 4  and  created_at < DATE(NOW() - INTERVAL 3 MONTH) and seller_id= '.$user->id.') AS rate4, (SELECT COUNT(*) FROM comments WHERE rate = 5  and  created_at < DATE(NOW() - INTERVAL 3 MONTH)  and seller_id= '.$user->id.') AS rate5')
       ->get()->first();



        $data['month1count'] = $month1count;
        $data['month2count'] = $month2count;
        $data['month3count'] = $month3count;
        

        MetaTag::set('title', __(":name's ".__('messages.profile_tab3'), ['name' => $user->username]));
        MetaTag::set('sub_id', 3);


        return view('profile.reviews', $data);
    }

    public function report($listing)
    {
        $data = [];
        $data['listing'] = $listing;

        if(view()->exists('listing.report')){
            return view('listing.report', $data);
        }
        return view('moderatelistings::listing.report', $data);
    }

    public function blockUser($user) {

        if($user == null) {
            abort(404);
        }

        if($user == auth()->user()) {
            abort(404);
        }



        $checkBlock = UserBlock::where('user_id',auth()->user()->id)->where('user_id_blocked',$user->id)->get();

        if($checkBlock->count() != 0) {
            return redirect()->route('profile',$user->username)->with('message',__('validation.blocked'));
        }


        $block = new UserBlock;
        $block->user_id = auth()->user()->id;
        $block->user_id_blocked = $user->id;
        $block->save();

        return redirect()->route('profile',$user->username)->with('message',__('validation.blocked_now'));

    }

    public function unblockUser($user) {
        if($user == null) {
            abort(404);
        }
        

        if($user == auth()->user()) {
            abort(404);
        }

        $checkBlock = UserBlock::where('user_id',auth()->user()->id)->where('user_id_blocked',$user->id)->get()->first();
        if($checkBlock != null) {
            $checkBlock->delete();
            return redirect()->route('profile',$user->username)->with('message',__('validation.blocked_1'));
        } else {
            return redirect()->route('profile',$user->username)->with('error',__('validation.blocked_2'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    public function submit($user, Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        if ($validator->fails()) {
            return redirect('profile/'.$user->username.'#report')->withErrors($validator)
                ->withInput();
        }


        ReportedListing::create([
            'reason' => $request->input('reason'),
            'notes' => $request->input('notes'),
            'user_id' => auth()->user()->id,
            'reported_user' => $user->id,
        ]);

        return redirect('profile/'.$user->username.'#report')->with('message',__('validation.report_received'));
    
    }

       /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeMessageViaProfile($username,Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'captcha' => ['required', new CaptchaCheck]
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

       
        $user = User::where('username',$username)->first();
        if($user->id == auth()->user()->id) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        if($user == null) {
            abort(404);
        }

        $message = $request->input('message');

        if($request->input('encrypt') != null) {
            if($user->pgp_key != null) {
                $gpg = new gnupg();
                $info = $gpg->import($user->pgp_key);
                $gpg->addencryptkey($info['fingerprint']);
                $message = $gpg->encrypt($request->input('message'));
            }
        }

        Talk::setAuthUserId(auth()->user()->id);
        Talk::sendMessageByUserId($user->id, $message);
        $conversationId = Talk::isConversationExists($user->id);
        $user->increment('unread_messages');


        return redirect(route('inbox.show', $conversationId));
    }


    public function star($id) {
        if(!auth()->check()) {
            return false;
        }
        $listing = Listing::find($id);
        $listing->toggleFavorite();
        return view('listing::widgets.favorite', compact('listing'));
    }

    public function follow($user) {
        if($user == null || $user == auth()->user()) {
            abort(404);
        }

        if(auth()->user()->getIsFollowed($user)) {
            auth()->user()->unfollow($user);
            return redirect()->back()->with('message',__('validation.follow_now').' '.$user->username);
        } else {
            auth()->user()->follow($user);
            return redirect()->back()->with('message',__('validation.unfollowed').' '.$user->username);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('listing::create');
    }

    public function createMessageViaListing($user)
    {
        MetaTag::set('title', __('messages.message_title'));
        return view('profile.messagecreate')->with('profile',$user);
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
        return view('listing::show');
    }

}
