<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\CaptchaCheck;
use DB;
use Carbon\Carbon;
use MetaTag;




class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function indexPage()
    {
        MetaTag::set('title', __('messages.reset_password_title'));

        
        return view('auth.passwords.email');
    }

    protected function validateReset(Request $request)
    {
    $this->validate($request, [
        'username' => 'required|string',
        'mnemonic' => 'required|string',
        'captcha' => ['required', new CaptchaCheck]
        ]);
    }

    protected function validatePasswordReset(Request $request)
    {
    $this->validate($request, [
        'password' => 'required|confirmed'
    ]);
    }


    public function verifyPage(Request $request)
    {
        $this->validateReset($request);

        MetaTag::set('title', __('messages.reset_password_title'));


    $userok = DB::table('users')
    ->where([
        ['username', '=', $request->username],
        ['mnemonic', '=', $request->mnemonic]
    ])->first();


    if (!isset($userok)) {
        return redirect()->back()->withErrors(["Wrong."]);
    }

    DB::table('password_resets')->insert([
    'username' => $request->username,
    'token' => str_random(60),
    'created_at' => Carbon::now()
    ]);

    $tokenData = DB::table('password_resets')
    ->where('username', $request->username)->first();

    return redirect('/account/reset/token/'.$tokenData->token);

}

    public function resetPasswordPage($token) {

        MetaTag::set('title', __('messages.reset_password_title'));

        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();

        if (!isset($tokenData)) { 
           return view('auth.passwords.email'); 
        }

        $username = $tokenData->username;

        return view('auth.passwords.reset',compact('token','username'));

    }

    public function savePasswordReset(Request $request) {

        $this->validatePasswordReset($request);

        $password = $request->password;
        // Validate the token
    $tokenData = DB::table('password_resets')
    ->where('token', $request->token)->first();
    
    if (!$tokenData) return view('auth.passwords.email');
    

    //Hash and update the new password

    $user = User::where('username', $tokenData->username)->first();
    $user->password = \Hash::make($password);
    $user->update(); //or $user->save();

    //Delete the token
    DB::table('password_resets')->where('username', $user->username)
    ->delete();

    return redirect('/login');

    }
}