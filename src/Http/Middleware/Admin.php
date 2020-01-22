<?php

namespace Yoan1005\Admigen\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ( Auth::check() )
        {
            if(Auth::user()->admin == 1) {
              return $next($request);
            } else {
              return redirect(route('admin.loginPage'))->with("errors", "Vous n'êtes pas autorisé à accéder à cette page");
            }

        }
        return redirect(route('admin.loginPage'))->with("errors", "Vous n'êtes pas autorisé à accéder à cette page");
    }
}
