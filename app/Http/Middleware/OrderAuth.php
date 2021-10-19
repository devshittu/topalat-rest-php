<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    /*public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }*/


    //Replace handle function:
    public function handle($request, Closure $next)
    {
        dd('OrderAuth.php::handle() ', Auth::user());
        //The following line(s) will be specific to your project, and depend on whatever you need as an authentication.
        $isAuthenticatedAdmin = (Auth::check() && Auth::user()->admin == 1);

        //This will be excecuted if the new authentication fails.
        if (!$isAuthenticatedAdmin)
            return redirect('/login')->with('message', 'Authentication Error.');
        return $next($request);
    }
}
