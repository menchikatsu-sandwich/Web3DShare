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
            'username'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'username'=>$r->username,
            'email'=>$r->email,
            'password'=>bcrypt($r->password)
        ]);

        Auth::login($user);

        return $r->wantsJson()
            ? response()->json($user)
            : redirect('/');
    }

    public function login(Request $r)
    {
        $loginField = filter_var($r->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        if (!Auth::attempt([$loginField => $r->input('email'), 'password' => $r->input('password')])) {
            return back()->withErrors(['login' => 'salah']);
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
            : redirect($redirectUrl);
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/');
    }
}

