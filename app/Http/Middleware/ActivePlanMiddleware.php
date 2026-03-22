<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivePlanMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        //do not check active plan as of now
        return $next($request);

        if (Auth::user()->subscription){
            if (Auth::user()->subscription->subscription_end->isFuture()) {
                return $next($request);
            }
        }

        return api_response(null, false, 403, 'no_active_plan');
    }
}
