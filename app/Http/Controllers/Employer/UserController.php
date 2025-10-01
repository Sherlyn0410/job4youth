<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        $employer = Auth::guard('employer')->user();
        
        return view('employer.user.profile', compact('employer'));
    }

    public function updateProfile(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        
        // User profile update logic will be implemented later
        
        return redirect()->route('employer.user.profile')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        // Password update logic will be implemented later
        
        return redirect()->route('employer.user.profile')->with('success', 'Password updated successfully!');
    }
}