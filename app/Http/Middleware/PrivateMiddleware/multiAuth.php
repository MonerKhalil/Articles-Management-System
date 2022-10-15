<?php

namespace App\Http\Middleware\PrivateMiddleware;

use App\Application\Application;
use Closure;
use Illuminate\Http\Request;

class multiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next,string $Auth): \Illuminate\Http\JsonResponse
    {
        try {
            $user = auth("user")->user();
            if(!is_null($user)){
                if(in_array($user->role,explode('|', $Auth)))
                    return $next($request);
            }
            return Application::getApp()->getHandleJson()->ErrorsHandle("access",
                Application::getApp()->getErrorMessages()["access"]);
        }catch (\Exception $exception){
            return Application::getApp()->getHandleJson()->ErrorsHandle("exception",$exception->getMessage());
        }
    }
}
