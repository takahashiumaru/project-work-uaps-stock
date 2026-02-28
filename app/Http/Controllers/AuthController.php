<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ...existing code...

    public function actionlogin(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        // Ambil nilai username dan password
        $username = $validated['username'];
        $password = $validated['password'];

        // Kunci throttle gabungkan username (lowercase) dan IP
        $throttleKey = 'login|'.Str::lower($username).'|'.$request->ip();

        // Batas percobaan dan waktu kedaluwarsa (detik)
        $maxAttempts = 5;
        $decaySeconds = 60;

        // Cek apakah sudah terlalu banyak percobaan
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()
                ->back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }

        // Susun kredensial (NIP disimpan di kolom id)
        $credentials = [
            'id' => $username,
            'password' => $password,
        ];

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            // Login berhasil: reset throttle dan regenerasi sesi
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->route('home');
        }

        // Login gagal: catat percobaan dan kembalikan error
        RateLimiter::hit($throttleKey, $decaySeconds);

        return redirect()
            ->back()
            ->withInput($request->only('username'))
            ->withErrors(['username' => 'NIP atau password salah.']);
    }

    // ...existing code...
}
