<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if ($this->isApiRequest($r)) {
            $token = $user->createToken($r->input('device_name', 'api-token'))->plainTextToken;

            return $this->apiData([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Account created.', 201);
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Account created. Welcome to Web3DShare.');
    }

    public function login(Request $r)
    {
        $r->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($r->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($this->isApiRequest($r)) {
            $user = User::where($loginField, $r->input('email'))->first();

            if (!$user || !Hash::check($r->input('password'), $user->password)) {
                return $this->apiError('Email/username or password is incorrect.', 401);
            }

            $token = $user->createToken($r->input('device_name', 'api-token'))->plainTextToken;
            $redirectUrl = in_array($user->role, ['admin', 'moderator']) ? '/panel' : '/';

            return $this->apiData([
                'redirect' => $redirectUrl,
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Logged in successfully.');
        }
    
        if (!Auth::attempt([$loginField => $r->input('email'), 'password' => $r->input('password')])) {
            return back()
                ->withErrors(['login' => 'Email/username or password is incorrect.'])
                ->withInput($r->only('email'));
        }
    
        $r->session()->regenerate();

        // 1. Get the authenticated user.
        $user = Auth::user();

        // 2. Set the default destination for regular users.
        $redirectUrl = '/';

        // 3. Check whether the user is an admin or moderator.
        if ($user->role === 'admin' || $user->role === 'moderator') {
            $redirectUrl = '/panel';
        }
    
        return redirect($redirectUrl)->with('success', 'Logged in successfully.');
    }

    public function logout(Request $r)
    {
        if ($this->isApiRequest($r)) {
            $r->user()?->currentAccessToken()?->delete();

            return $this->apiData([], 'Logged out successfully.');
        }

        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }

    private function isApiRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->wantsJson();
    }
}

