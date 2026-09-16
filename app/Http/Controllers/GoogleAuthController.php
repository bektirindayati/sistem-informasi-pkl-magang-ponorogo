<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            $avatarPath = $this->downloadAvatar(
                $googleUser->getAvatar(),
                $googleUser->getId()
            );

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $avatarPath ?? $user->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $avatarPath,
                    'role' => 'user',
                ]);
            }

            Auth::login($user);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');

        } catch (Exception $e) {
            return redirect('/login')
                ->with('error', 'Gagal masuk menggunakan akun Google.');
        }
    }

    private function downloadAvatar(
        ?string $url,
        string $googleId
    ): ?string {
        if (!$url) {
            return null;
        }

        try {
            $response = Http::timeout(5)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $filename = "avatars/{$googleId}.jpg";

            Storage::disk('public')->put(
                $filename,
                $response->body()
            );

            return $filename;

        } catch (Exception $e) {
            return null;
        }
    }
}