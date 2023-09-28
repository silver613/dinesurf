<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        // $response = $next($request);
        // header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE');
        // header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
        // header('Access-Control-Allow-Origin', '*');
        return $next($request);
    }
}
