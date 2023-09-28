<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as ResourcesUser;
use App\Services\AllServices\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            Admin::apiLogin($request->identifier, $request->password);
        } catch (\Throwable $th) {
            Helper::apiRes($th->getMessage(), [], false, $th->getCode());
        }

        $token = Auth::user()->createToken(config('app.key'))->plainTextToken;
        $mesaage = 'Successfully logged in';

        // Authentication Succeeded
        return Helper::apiRes($mesaage, ['token' => $token], true, 200);
    }

    public function user()
    {
        return Helper::apiRes('Logged in User', new ResourcesUser(Auth::user()));
    }
}
