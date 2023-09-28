<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $vendor = session('current_vendor');
        if (! $vendor) {
            return $next($request);
        }

        if ($vendor) {
            if ($vendor->subscribed || $vendor->free_trial) {
                return $next($request);
            }
        }

        return redirect()->route('vendors.billing')->with(['You need to susbcribe to proceed']);
    }
}
