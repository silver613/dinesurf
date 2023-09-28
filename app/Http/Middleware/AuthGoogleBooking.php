<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use App\Services\AllServices\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class AuthGoogleBooking
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
        info("Google Booking Request for ".request()->path());
        info(json_encode(request()->all()));

        if (App::environment('production')) {
            $username = request()->server('PHP_AUTH_USER');
            $password = request()->server('PHP_AUTH_PW');
        } else {
            $username = 'providence@reftek.co';
            $password = 'favour007';
        }

        if (!$username || !$password) {
            return response()->json(['message' => 'Unauthenticated.']);
            info("Un-Authenticated Google Booking Request");
        }

        try {
            Admin::apiLogin($username, $password);
        } catch (\Throwable $th) {
            Helper::apiRes($th->getMessage(), [], false, $th->getCode());
        }

        info("Authenticated Google Booking Request");

        return $next($request);
    }
}
