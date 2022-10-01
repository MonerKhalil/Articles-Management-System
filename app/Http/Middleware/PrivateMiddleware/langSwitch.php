<?php

namespace App\Http\Middleware\PrivateMiddleware;

use Closure;
use Illuminate\Http\Request;

class langSwitch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale("ar");
        if($request->lang === "en"){
            app()->setLocale("en");
        }
        $user = auth("user")->user();
        if(!is_null($user)){
            app()->setLocale($user->setting_lang);
        }
        return $next($request);
    }
}
