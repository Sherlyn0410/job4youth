<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse|JsonResponse
    {
        // If this is an AJAX request (modal), return just the modal content
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'html' => view('components.auth.login-form')->render()
            ]);
        }
        
        // For direct access to /login, redirect to home with login modal open
        return redirect()->route('home')->with('show_login_modal', true);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Set a flash message for login success
            session()->flash('login_success', true);

            // If this is an AJAX request (from modal), return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $request->session()->get('url.intended', '/dashboard')
                ]);
            }

            // Determine redirect URL for regular form submissions
            $intendedUrl = $request->session()->get('url.intended');
            
            // If coming from jobs page, redirect back to jobs
            $referer = $request->headers->get('referer', '');
            if (str_contains($referer, '/jobs') || ($intendedUrl && str_contains($intendedUrl, '/jobs'))) {
                return redirect()->route('jobs.index')->with('login_success', true);
            }

            // Default redirect
            return redirect()->intended($intendedUrl ?: '/dashboard')->with('login_success', true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // If this is an AJAX request (from modal), return JSON error response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login failed',
                    'errors' => $e->errors()
                ], 422);
            }

            // For regular form submissions, redirect back with errors
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput($request->only('email', 'remember'));
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
