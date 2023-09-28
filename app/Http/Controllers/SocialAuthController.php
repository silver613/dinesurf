<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AllServices\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function googleRedirect(Request $request)
    {
        session(['current_url' => $request->intendedUrl]);

        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        // dd(request()->query('code'));
        try {
            $googleUser = Socialite::driver('google')->user();
            $existUser = User::where('email', $googleUser->email)->first();

            if ($existUser) {
                Auth::loginUsingId($existUser->id);
            } else {
                $user = new User;
                $names = (explode(' ', $googleUser->name));
                $user->name =  $googleUser->name;
                $user->first_name = $names[0];
                $user->last_name = $names[1];
                $user->email = $googleUser->email;
                $user->google_id = $googleUser->id;
                $user->image = null;
                // $googleUser->avatar;
                $user->password = md5(rand(1, 10000));
                $user->save();
                Auth::loginUsingId($user->id);
            }

            if (session('current_url')) {
                return redirect()->route('back');
            } else {
                return Admin::authResponse('login', $request->intendedUrl);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callable URL from facebook
     */
    public function facebookCallback(Request $request)
    {
        if (! $request->has('code') || $request->has('denied')) {
            return redirect('/');
        }

        try {
            $facebookUser = Socialite::driver('facebook')->user();
            $existUser = User::where('email', $facebookUser->email)->first();

            if ($existUser) {
                Auth::loginUsingId($existUser->id);
            } else {
                $user = new User;
                $names = (explode(' ', $facebookUser->name));
                $user->name = $facebookUser->name;
                $user->first_name = $names[0];
                $user->last_name = $names[1];
                $user->email = $facebookUser->email;
                $user->facebook_id = $facebookUser->id;
                $user->image = $facebookUser->avatar;
                $user->password = md5(rand(1, 10000));
                $user->save();
                Auth::loginUsingId($user->id);
                if (session('current_url')) {
                    return redirect()->route('back');
                } else {
                    return redirect()->route('dashboard');
                }
                // return redirect()->to('/dashboard');
            }

            if (session('current_url')) {
                return redirect()->route('back');
            } else {
                return redirect()->route('dashboard');
            }
            // return redirect()->to('/dashboard');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
