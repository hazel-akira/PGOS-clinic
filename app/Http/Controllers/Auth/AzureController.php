<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AzureController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('azure')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $azureUser = Socialite::driver('azure')->user();

        $user = User::firstOrCreate(
            ['email' => $azureUser->getEmail()],
            [
                'name' => $azureUser->getName() ?: ($azureUser->getNickname() ?: $azureUser->getEmail()),
                'password' => bcrypt(Str::random(40)),
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}

