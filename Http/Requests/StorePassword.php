<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CaptchaCheck;


class StorePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'       => 'confirmed|different:old_password|min:8|max:100|nullable',
            'pin'                    => 'min:6|max:6|confirmed|string|different:pin_current|nullable',
            'pin_confirmation'  => 'min:6|max:6|string|nullable',
            'captcha' => ['required', new CaptchaCheck]
        ];
    }
}
