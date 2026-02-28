<?php

namespace App\Http\Controllers;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function actionlogin(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'], // NIP
            'password' => ['required', 'string'],
        ]);

        $userId = (int) $validated['username'];
        $user = User::query()->where('id', '=', $userId)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'NIP atau password salah.');
        }

        // cek password manual (jangan Auth::attempt dulu, karena itu langsung login)
        if (!Hash::check($validated['password'], $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'NIP atau password salah.');
        }

        // guard: kalau kolom OTP belum ada, fallback login normal
        if (!Schema::hasColumn('users', 'otp_code') || !Schema::hasColumn('users', 'otp_expires_at')) {
            Auth::login($user);
            return redirect()->route('home');
        }

        // pastikan email ada
        if (empty($user->email)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Email user belum terdaftar. Hubungi admin.');
        }

        // generate otp & email
        $ttlMinutes = 5;
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes($ttlMinutes),
        ])->save();

        Mail::to($user->email)->send(new LoginOtpMail($otp, $ttlMinutes));

        // session untuk verifikasi OTP
        $request->session()->put('otp_user_id', $user->id);

        // IMPORTANT: pastikan belum ada session login yang tersisa
        Auth::logout();

        return redirect()->route('otp.show');
    }
}
