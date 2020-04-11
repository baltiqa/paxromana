<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Auth;

class MmenomicVerify extends \Illuminate\Auth\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ( auth()->check() && auth()->user()->verified == 1 )
        {
        } else {
            return redirect()->to('/mnemonic');
        }

        return $next($request);
    }

}
