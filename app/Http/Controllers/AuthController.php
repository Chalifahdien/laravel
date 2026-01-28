<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        if (!in_array($user->role, ['admin', 'user'])) {
            return back()->withErrors(['email' => 'Akun Anda tidak memiliki izin akses.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // if (Auth::user()->role == 'admin') {
            //     return redirect()->intended('/dashboard');
            // }
            return redirect()->intended('/');
        }

        return back()->withErrors(['password' => 'Password salah.']);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
