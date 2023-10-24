<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function handleGithubRedirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $user = Socialite::driver('github')->user();

            $userExist = User::where('email', $user->email)->first();

            if ($userExist) {
                $userExist->oauth_id = $user->id;
                $userExist->oauth_type = 'github';
                $userExist->save();

                Auth::login($userExist);
                return redirect()->route('dashboard');
            } else {

                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'oauth_id' => $user->id,
                    'oauth_type' => 'github',
                    'password' => Hash::make($user->id)
                ]);

                Auth::login($newUser);
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
