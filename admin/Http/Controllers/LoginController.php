<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Request\Admin\LoginRequest;
use App\Rules\CaptchaCheck;
use Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/panel';


    /**
     * Shows the admin login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        if(auth()->user() != null) {
          if(auth()->user()->is_admin == 1 || auth()->user()->is_mod == 1 ) {
            return redirect('/panel');
           }
        }

        return view('panel::auth.login');
    }

    public function login(Request $request)
    {
        request()->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => ['required', new CaptchaCheck]
        ]);
 
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/panel');
        }
        return redirect()->back()->with('error','fout');
    }

}

