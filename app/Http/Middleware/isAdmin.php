<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
//use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class isAdmin
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->id == 6) {
           // return route('register');   
            return $next($request);
        }else{
            return back();
        }

       // abort(500);
    }
}