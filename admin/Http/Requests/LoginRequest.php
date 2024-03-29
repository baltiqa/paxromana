<?php

namespace App\Http\Request\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CaptchaCheck;


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required'],
            'password' => ['required'],
            'captcha' => ['required', new CaptchaCheck]
        ];
    }
}
