<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorDashboard
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
        // dd($vendor);
        if ($vendor) {
            return redirect()->route('vendors.dashboard');
        }

        return $next($request);
    }
}
