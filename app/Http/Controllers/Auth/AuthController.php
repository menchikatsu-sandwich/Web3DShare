<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'username' => ['required', 'string', 'max:50', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$/', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'terms_accepted' => ['accepted'],
        ]);

        $user = User::create([
            'username'=>$r->username,
            'email'=>$r->email,
            'password'=>bcrypt($r->password)
        ]);

        Auth::login($user);

        return $r->wantsJson()
            ? response()->json($user)
            : redirect('/')->with('success', 'Account created. Welcome to Web3DShare.');
    }

    public function login(Request $r)
    {
        $r->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($r->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        if (!Auth::attempt([$loginField => $r->input('email'), 'password' => $r->input('password')])) {
            if ($r->wantsJson()) {
                return response()->json(['error' => 'Email/username or password is incorrect.'], 401);
            }

            return back()
                ->withErrors(['login' => 'Email/username or password is incorrect.'])
                ->withInput($r->only('email'));
        }
    
        $r->session()->regenerate();

        // 1. Ambil data user yang berhasil login
        $user = Auth::user();

        // 2. Tentukan tujuan default (user biasa)
        $redirectUrl = '/';

        // 3. Cek apakah role-nya admin atau moderator
        if ($user->role === 'admin' || $user->role === 'moderator') {
            $redirectUrl = '/panel'; // Sesuaikan dengan route admin-mu
        }
    
        return $r->wantsJson()
            ? response()->json(['msg' => 'ok', 'redirect' => $redirectUrl])
            : redirect($redirectUrl)->with('success', 'Logged in successfully.');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}

