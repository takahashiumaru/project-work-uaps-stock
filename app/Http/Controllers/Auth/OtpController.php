<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) return redirect()->route('login');

        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('otp_user_id');
        if (!$userId) return redirect()->route('login')->with('error', 'Session OTP berakhir. Silakan login ulang.');

        $user = User::find($userId);
        if (!$user) return redirect()->route('login')->with('error', 'User tidak ditemukan.');

        $now = Carbon::now();
        if (!$user->otp_code || !$user->otp_expires_at || $now->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP sudah kadaluarsa. Klik kirim ulang OTP.']);
        }

        if ($request->otp !== $user->otp_code) {
            return back()->withErrors(['otp' => 'OTP salah.']);
        }

        // OTP ok: clear & login
        $user->forceFill(['otp_code' => null, 'otp_expires_at' => null])->save();

        $request->session()->forget('otp_user_id');
        Auth::login($user);

        return redirect()->route('home');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);

        $ttlMinutes = 5;
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes($ttlMinutes),
        ])->save();

        Mail::to($user->email)->send(new LoginOtpMail($otp, $ttlMinutes));

        return back()->with('success', 'OTP baru sudah dikirim ke email Anda.');
    }
}
