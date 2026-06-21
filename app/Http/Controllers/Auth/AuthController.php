<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if ($this->isApiRequest($request)) {
            $token = $user->createToken($data['device_name'] ?? 'api-token')->plainTextToken;

            return $this->apiData([
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Account created.', 201);
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Account created. Welcome to Web3DShare.');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $loginField = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($this->isApiRequest($request)) {
            $user = User::where($loginField, $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return $this->apiError('Email/username or password is incorrect.', 401);
            }

            $token = $user->createToken($data['device_name'] ?? 'api-token')->plainTextToken;
            $redirectUrl = in_array($user->role, ['admin', 'moderator']) ? '/panel' : '/';

            return $this->apiData([
                'redirect' => $redirectUrl,
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 'Logged in successfully.');
        }

        if (! Auth::attempt([$loginField => $data['email'], 'password' => $data['password']])) {
            return back()
                ->withErrors(['login' => 'Email/username or password is incorrect.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $user = Auth::user();

        $redirectUrl = '/';

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
