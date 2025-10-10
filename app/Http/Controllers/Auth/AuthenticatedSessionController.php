<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login-form');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            // Set a flash message for login success
            session()->flash('login_success', true);

            // If this was an AJAX request, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => $request->session()->get('url.intended', '/dashboard')
                ]);
            }

            // Check if user was trying to do a job action before login
            $intendedUrl = $request->session()->get('url.intended', '/dashboard');
            
            // If coming from jobs page, redirect back to jobs
            if (str_contains($request->headers->get('referer', ''), '/jobs') || 
                str_contains($intendedUrl, '/jobs')) {
                return redirect()->route('jobs.index')->with('login_success', true);
            }

            return redirect()->intended($intendedUrl)->with('login_success', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Keep the login modal open when there are validation errors
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->only('email', 'remember'))
                ->with('show_login_modal', true);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
