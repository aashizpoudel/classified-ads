<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SetApplicationLanguage
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
        //Set Language
        App::setLocale(session('lang') ? session('lang') : Config::get('app.locale'));

        //Share Logged In User
        if (Auth::check()){
            view()->share('logged_user', Auth::user());
        }


        return $next($request);
    }
}
