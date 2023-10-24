<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class LinkedinController extends Controller
{
    public function handleLinkedinRedirect()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function handleLinkedinCallback()
    {
        try {

            $user = Socialite::driver('linkedin')->user();

            $userExists = User::where('email', $user->email)->first();

            if ($userExists) {
                $userExists->oauth_id = $user->id;
                $userExists->oauth_type = 'linkedin';
                $userExists->save();

                Auth::login($userExists);
                return redirect()->route('dashboard');
            } else {

                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'oauth_type' => 'linkedin',
                    'password' => Hash::make($user->id)
                ]);
            }
            Auth::login($newUser);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
