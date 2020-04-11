<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Securimage;
use App\Models\Securimage_Color;

class CaptchaCheck implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $image = new Securimage();

        return $image->check($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You have entered the wrong captcha code, guess Chuck Noris was not here around';
    }
}
