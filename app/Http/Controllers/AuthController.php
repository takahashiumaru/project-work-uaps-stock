<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ...existing code...

    public function actionlogin(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $credentials = [
            'id' => $validated['username'],     // NIP disimpan di kolom id
            'password' => $validated['password']
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return redirect()
            ->back()
            ->withInput($request->only('username'))
            ->with('error', 'NIP atau password salah.');
    }

    // ...existing code...
}
