<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\PGP_2FA;
use Illuminate\Http\Request;
use App\Models\Securimage;
use App\Models\Securimage_Color;
use App\Rules\CaptchaCheck;
use Illuminate\Support\Str;
use MetaTag;
use Auth;
use Validator;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/antiphishing';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest'])->except('logout','mnemonicPage','mnemonicPost','antiPhishing','imageAntiphishing','captcha');
        $this->redirectTo = route('antiphishing');
    }


    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => ['required', new CaptchaCheck]
        ]);
    }

    
    /**
     * Overrides method of username @Auth
     *a
     *
     */
    public function username()
    {
        return 'username';
    }

    protected function redirectTo()
    {
        return $this->redirectTo;
    }

    public function antiPhishing() {  

        MetaTag::set('title', __('messages.antiphishing_title'));

        $random = rand(1,6);

        return view('auth.confirmationphishing',compact('random'));
    }


    public function imageAntiphishing(){
        $image  = public_path().'/web/images/antiphishing.png';

        $jpg_image = imagecreatefrompng ($image);
        $color= imagecolorallocate($jpg_image, 0, 0, 0);
        $text = env('APP_URL');
        $font_path  = public_path().'/web/images/roman.ttf';

        imagettftext($jpg_image, 15, 0, 0, 555, $color, $font_path, $text);
        header('Content-type: image/png');
        imagejpeg($jpg_image);
    }
    public function pGP2FACheckP() {  

        MetaTag::set('title', __('messages.fa2_title'));


        if(auth()->user() != null || session()->get('pgp-secret-hash') == null ) {
            return redirect()->route('browse');
        }


        return view('auth.2fa');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->forget(['from']);
        $request->session()->invalidate();

        return back();
    }

    public function showLoginForm(Request $request)
    {

	 if($request->session()->get('submit_login') == null) {
        return redirect('/antiddoss');
      }


        MetaTag::set('title', __('messages.login_button'));

        return view('auth.login');
    }

    public function antiDdosPage(Request $request)
    {
        MetaTag::set('title', __("Anti DDoS"));


     if($request->session()->get('submit_login') != null) {
       	return redirect('/login');
      }

        return view('auth.antidoss');
    }


  public function submitAntiDdos(Request $request)
    {
        $rules = array(
            'captcha' => ['required', new CaptchaCheck]
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

      session()->put('submit_login', "yes");

       return redirect('/login');

    }




    public function verifyUrl(Request $request)
    {

	 if($request->session()->get('submit_login') == null) {
        return redirect('/antiddoss');
      }

        MetaTag::set('title', __('messages.verifyurl_title'));

        $pgp = new PGP_2FA;
        $message =  $pgp->sign('This signature has been created by the Pax Romana PGP Public key. 
         
You are now on the Pax Romana URL ' . env('WEBSITE_URL') . ' 

If this URL doesn\'t match with in the Browser  dont login Sent the support a message. Verify this message before deposit!

');

        return view('auth.verify',compact('message'));
    }

    public function mnemonicPage()
    {
        MetaTag::set('title', __('messages.mmenomic_title'));

        if(auth()->user() != null) {
            if(auth()->user()->verified == 0) {
            return view('auth.mnemonic');
            }
        }
     return redirect()->route('antiphishing');
    }

    public function mnemonicPost(Request $request)
    {
        MetaTag::set('title', __('messages.mmenomic_title'));
        if(auth()->user() != null) {
            if(auth()->user()->verified == 0) {
              $user =  User::Find(auth()->user()->id);
                if(($user->mnemonic == $request->input('mnemonic')) && ($user->withdrawpin == $request->input('withdrawpin')) ) {
                    $user->verified = 1;
                    $user->save();
                    return redirect()->route('antiphishing');
                } else {
                   return back()->withErrors(['field_name' => ['Your custom message here.']]);
                  }

            }
        }
     return redirect()->route('browse');
    }
    


    public function authenticated($request, $user)
    {
    
        if (auth()->user()->otp == 1) {
            return self::startTwoFactorAuthProcess($request, $user);
        }
    
        $redirect_to = '/antiphishing';

        return redirect($redirect_to);
    }

    public function PGPauthenticated($request, $user)
    {
        $redirect_to = session()->pull('from', $this->redirectTo);

        return redirect($redirect_to);
    }

    private function startTwoFactorAuthProcess(Request $request, $user)
    {
    auth()->logout();
    $request->session()->put(
        'userid', array_merge(['id' => $user->id])
    );

    $pgp_message = $this->generate2FACode($user->pgp_key);

    session()->put('suckmynuts', $pgp_message);

    return redirect()->route('2fa_factor');
    
    }

    public function generate2FACode($pgp_key) {

        $pgp_2fa = new PGP_2FA();

      $pgp_2fa->generateSecret();  
      $message = $pgp_2fa->encryptSecret($pgp_key);
     
        return $message;
    }

    public function verifyTwoFactorAuth(Request $request) {
        $user = User::findOrFail($request->session()->get('userid')['id']);

        $pgp_2fa = new PGP_2FA();

        if($pgp_2fa->compare($request->input('code'),$request->session()->get('pgp-secret-hash'))) {

            $request->session()->regenerate();
            $request->session()->forget('suckmynuts');
            $request->session()->forget('pgp-secret-hash');
            $request->session()->forget('userid');

            auth()->login($user); 

            return $this->PGPauthenticated($request, $user);
        } else {
            return back()->withErrors(['field_name' => ['Invalid code']]);
        }
    }


    public function captcha() {
        $captcha = new Securimage();

        $captcha->image_signature = env('APP_URL');
        $captcha->image_height = "80";
        $captcha->image_width = "250";
        $captcha->text_color = new Securimage_Color("#000");
        $captcha->line_color = new Securimage_Color("#707070");
        $captcha->signature_color = new Securimage_Color("#eda566");
        $captcha->num_lines=0;
        $captcha->noise_level=0;
        $captcha->use_random_boxes = true;
        
        $captcha->show();

        return view('auth.captcha', $captcha);
    }

}
