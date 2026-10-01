<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function magicLogin($role)
    {
        // For Hackathon Judges: 1-click login
        $user = User::where('role', $role)->first();
        if ($user) {
            Auth::login($user);
            session()->regenerate();
            return $this->redirectBasedOnRole($role);
        }
        
        return redirect()->route('login')->withErrors(['email' => 'Role not seeded.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectBasedOnRole($role)
    {
        return match ($role) {
            'store_manager' => redirect()->route('store.dashboard'),
            'dispatcher' => redirect()->route('dispatch.overview'),
            'loader' => redirect()->route('loader.queue'),
            'driver' => redirect()->route('driver.route'),
            default => redirect()->route('login'),
        };
    }
}
