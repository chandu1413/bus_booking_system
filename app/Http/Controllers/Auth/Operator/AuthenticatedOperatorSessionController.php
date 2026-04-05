<?php

namespace App\Http\Controllers\Auth\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedOperatorSessionController extends Controller
{
    public function create()
    {
        return view('auth.operator.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $request->session()->regenerate();
        auth()->user()->update(['last_login_at' => now()]);

        if (auth()->user()->hasRole('Operator')) {
            if (auth()->user()->is_login_first_time == 0) {
                // $operator->update(['is_login_first_time' => 1]);

                return redirect()
                    ->route('operator.profile', auth()->user()->operator) // Pass operator instance to profile route
                    ->with('info', 'Welcome! Please complete your profile.');
            }
            return redirect()->intended(route('operator.dashboard'));
        }

        return redirect()->intended(route('operator.dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
