<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;
use Illuminate\Http\Request;

class ClientMiddleware
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
        $clientId = $request->header('X-Client-ID');
        $apiKey = $request->header('X-API-Key');

        $auth = Client::whereId($clientId)->whereApiKey($apiKey)
            ->exists();

        if (! $auth) {
            return response()->json(['message' => 'Unauthenticated.']);
        }

        return $next($request);
    }
}
