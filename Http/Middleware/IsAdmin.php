<?php

namespace App\Http\Middleware;
use Closure;

class isAdmin 
{
    public function handle($request, Closure $next)
    {   if(auth()->user() != null) {
          if(auth()->user()->is_admin == 1 || auth()->user()->is_mod == 1 ) {
            return $next($request);
         }
        }
        return redirect()->back();
    }

}
