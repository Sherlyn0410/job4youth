<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employer;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Add this line temporarily for debugging
        logger('Employer login page accessed');
        
        // Redirect if already logged in
        if (Auth::guard('employer')->check()) {
            return redirect()->route('employer.dashboard');
        }
        
        return view('employer.login');
    }

    public function showRegister()
    {
        // Redirect if already logged in
        if (Auth::guard('employer')->check()) {
            return redirect()->route('employer.dashboard');
        }
        
        return view('employer.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('employer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('employer.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'employer_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employers,email',
            'phoneNo' => 'nullable|string|max:20',
            'company_size' => 'nullable|string|max:50',
            'company_type' => 'nullable|string|max:50',
            'company_description' => 'nullable|string|max:1000',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        $employer = Employer::create([
            'employer_name' => $request->employer_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phoneNo' => $request->phoneNo,
            'company_size' => $request->company_size,
            'company_type' => $request->company_type,
            'company_description' => $request->company_description,
            'password' => Hash::make($request->password),
            'country' => 'Malaysia', // Default value
        ]);

        Auth::guard('employer')->login($employer);

        return redirect()->route('employer.dashboard')->with('success', 'Account created successfully! Welcome to Job4Youth Employer Portal.');
    }

    public function logout(Request $request)
    {
        Auth::guard('employer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('employer.login')->with('success', 'You have been logged out successfully.');
    }
}