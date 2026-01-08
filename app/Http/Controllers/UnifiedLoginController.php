<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnifiedLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.unified_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'role'     => 'required|in:customers,staff,admin',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $role = $request->role;

        if (Auth::guard($role)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            switch ($role) {
                case 'customers':
                    return redirect()->route('customer.dashboard');

                case 'staff':
                    return redirect()->route('staff.dashboard');


                case 'admin':
                    return redirect()->route('admin.dashboard'); // ✅ FIXED
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials for ' . ucfirst($role),
        ]);
    }

    public function logout(Request $request)
    {
        $role = $request->role ?? 'customers';
        Auth::guard($role)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
