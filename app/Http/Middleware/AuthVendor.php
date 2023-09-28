<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthVendor
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
        $vendor = session('current_vendor');

        if (! $vendor) {
            return redirect()->route('vendors.dashboard');
        }

        return $next($request);
    }
}
