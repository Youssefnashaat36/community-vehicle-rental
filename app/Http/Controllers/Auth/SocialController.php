<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public const PROVIDERS = ['google', 'microsoft', 'apple'];

    public function redirect(string $provider): RedirectResponse
    {
        if (! config("services.$provider.client_id")) {
            return $this->fail(ucfirst($provider) . ' sign-in is not set up yet. Add its keys to your .env file.');
        }

        $driver = Socialite::driver($provider);

        if ($provider === 'apple') {
            return $driver->stateless()->scopes(['name', 'email'])->redirect();
        }

        return $driver->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        try {
            $driver = Socialite::driver($provider);
            $social = $provider === 'apple' ? $driver->stateless()->user() : $driver->user();
        } catch (\Throwable $e) {
            report($e);

            return $this->fail("We couldn't sign you in with " . ucfirst($provider) . '. Please try again.');
        }

        $email = $social->getEmail();

        if (! $email) {
            return $this->fail(ucfirst($provider) . ' did not share an email address with us.');
        }

        $user = User::where('provider', $provider)
            ->where('provider_id', $social->getId())
            ->first();

        if (! $user) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->forceFill([
                    'provider' => $provider,
                    'provider_id' => $social->getId(),
                    'avatar' => $user->avatar ?: $social->getAvatar(),
                ])->save();
            } else {
                $user = new User();
                $user->forceFill([
                    'name' => $social->getName() ?: Str::before($email, '@'),
                    'email' => $email,
                    'password' => Hash::make(Str::random(40)),
                    'provider' => $provider,
                    'provider_id' => $social->getId(),
                    'avatar' => $social->getAvatar(),
                    'email_verified_at' => now(),
                ])->save();
            }
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function fail(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['email' => $message]);
    }
}