<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Check if the request is for employer routes
            if ($request->is('employer/*')) {
                return route('employer.login');
            }
            
            // Default to regular login for job seekers
            return route('home');
        }
        
        return null;
    }
}