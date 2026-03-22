<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsMobileVerifiedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !auth()->user()->mobile_verified_at || auth()->user()->mobile_verified_at === null){
            return api_response(null, false, '301', 'Mobile is not verified');
        }
        return $next($request);
    }
}
