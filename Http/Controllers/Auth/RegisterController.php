<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Models\Referral;
use \FurqanSiddiqui\BIP39\BIP39;
use Illuminate\Http\Request;
use App\Rules\CaptchaCheck;
use MetaTag;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Alert;




class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/mnemonic';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function registered(Request $request, $user)
    {
        return redirect('mnemonic');
    }
    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:30|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'withdrawpin' => 'required|string|min:6|max:6',
            'captcha' => ['required', new CaptchaCheck]
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'withdrawpin' => $data['withdrawpin'],
            'password' => Hash::make($data['password']),
            'support_bitcoin' => 1 ,
            'support_monero' => 1,
            'support_litecoin' => 1,
        ]);
    }

    public function showRegistrationForm(Request $request,$referrer="",$id="")
    {
	 if($request->session()->get('submit_login') == null) {
        return redirect('/antiddoss');
         }



        MetaTag::set('title', __('messages.register_button'));

        $user = User::where("id",$id)->first();
        $ref_link = "";
        if($referrer == "referrer" && $user){
            $ref_link = "/referrer/".$id;
        }


        return view('auth.register')->with(["ref_link" => $ref_link]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request,$referrer="",$id="")
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $ref_user = false;

        if($referrer == "referrer"){
            $ref_user = User::where("id",$id)->first();
        }

        if($user && $ref_user){
            $refferal = new Referral();
            $refferal->user_id = $user->id;
            $refferal->referrer_id = $ref_user->id;
            $refferal->save();
        }


        $mnemonic = BIP39::Generate(12);
        $user->mnemonic= implode(" ", $mnemonic->words);
        $user->verified = 0;
        $user->trader_type = "buyer";
        $user->support_bitcoin = 1;
        $user->support_litecoin = 1;
        $user->support_monero = 1;
        $user->currency = "usd";
        $user->save();
        $profile = new ProfileController();
        
        $this->guard()->login($user);
        $notification = new NotificationController;
        $notification->notifyWelcomeUser($user->id);

        $profile->GenerateAddresses($user->id);
      


        return $this->registered($request, $user);
    }
}
