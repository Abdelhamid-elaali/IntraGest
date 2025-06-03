<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
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

            // Redirect Stock Manager users to the low stock page
            $user = Auth::user();
            if ($user && method_exists($user, 'isStockManager') && $user->isStockManager()) {
                return redirect()->route('stocks.low_stock');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Incorrect email or password. Please try again.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
