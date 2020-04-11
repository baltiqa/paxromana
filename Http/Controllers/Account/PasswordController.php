<?php

namespace App\Http\Controllers\Account;

use App\Rules\CaptchaCheck;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use MetaTag;
use Validator;


class PasswordController extends Controller
{
    public function index()
    {
        MetaTag::set('id', "1");
        MetaTag::set('normal_id', "1");
        MetaTag::set('title', __('messages.header_account'));


		return view('account.password');
    }

    public function resetWithdrawPin()
    {
        MetaTag::set('id', "1");
        MetaTag::set('normal_id', "1");
        MetaTag::set('title', __('messages.mnemonic_withdrawPin'));


		return view('account.resetwithdrawPIN');
    }

    public function storeResetWithdrawPin(Request $request)
    {
        $rules = array(
            'mnemonic'       => 'required',
            'pin'             => 'min:6|max:6|confirmed|string|required',
            'pin_confirmation'  => 'min:6|max:6|string|required',
            'captcha' => ['required', new CaptchaCheck]
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if($request->input('mnemonic') != auth()->user()->mnemonic) {
                return redirect()->back()
                ->withErrors(__('validation.mnemonic_wrong'));
            } else {
                auth()->user()->withdrawpin = $request->input('pin');
                auth()->user()->save();
                return redirect()->back()->with('message',__('validation.succesfull_saved_withdraw'));
            }
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'password'       => 'confirmed|different:old_password|min:8|max:100|nullable',
            'pin'             => 'min:6|max:6|confirmed|string|different:pin_current|nullable',
            'pin_confirmation'  => 'min:6|max:6|string|nullable',
            'captcha' => ['required', new CaptchaCheck]
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $updates = array('otp'=>$request->input('otp') == null ? 0 : 1);

            $password = $request->input('password');
            $passwordConfirmation = $request->input('password_confirmation');

            if(!empty($password)) {
                //Is the old password correct?
                if(!Hash::check($request->input('old_password'), auth()->user()->password)){
                    return redirect()->back()->withInput()->with('error',__('validation.old_password_Wrong'));
                }
                if($password === $passwordConfirmation) {
                    auth()->user()->password = Hash::make($password);
                } 
            } else {
                unset(auth()->user()->password);
                unset(auth()->user()->password_confirmation);
            }

            $pin = $request->input('pin');
            $pinConfirmation = $request->input('pin_confirmation');
            if(!empty($pin)) {
                if($request->input('pin_current') != auth()->user()->withdrawpin){
                    return redirect()->back()->withInput()->with('error',__('validation.old_pin_wrong'));
                }
                if($pin === $pinConfirmation) {
                    $updates['withdrawpin'] = $pin;
                } 
            }

            $currency = $request->input('currency');

            if(!empty($currency)) {
                $updates['currency'] = $currency;
            }

            
            $affectedRows = User::where('id', '=', auth()->user()->id)->update($updates);
            if($affectedRows){
            	if(!empty($password)) auth()->user()->save(); 
            	return redirect()->back()->withInput()->with('message',__('validation.succesfull_saved'));
            }
            else return redirect()->back()->withInput()->with('error',__('validation.error'));            
        }
    }

}
