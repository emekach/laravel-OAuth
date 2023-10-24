<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function handleGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    } // End Method

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            // check if user exist in db if it does , login without saving else create a new user detail


            /**  This wont work effectively if you have a custom form with users already registered */

            // $userExists = User::where('oauth_id', $user->id)
            // ->where('oauth_type', 'google')
            // ->first();

            $userExists = User::where('email', $user->email)
                ->first();

            if ($userExists) {
                $userExists->oauth_id = $user->id;
                $userExists->oauth_type = 'google';
                $userExists->save();

                Auth::login($userExists);

                return redirect()->route('dashboard');
            } else {
                $newUser = User::Create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'oauth_type' => 'google',
                    'password' => Hash::make($user->id),
                ]);
                Auth::login($newUser);

                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {

            return redirect()->route('login')->with('error', 'Failed to authenticate with Google');
        }
    } // End Method
}
