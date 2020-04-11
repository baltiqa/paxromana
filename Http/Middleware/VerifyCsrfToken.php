<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */

  protected $except = [
        'uploads*',
        'checkout/*',
        '*webhook*',
        'stripe',
        '/transactions/btc',
        '/transactions/ltc',
        '/transactions/xmr'
   ];
    protected function inExceptArray($request)
    {
        if($request->wantsJson()) {
            return true;
        }
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
