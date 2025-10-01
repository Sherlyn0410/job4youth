<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function profile()
    {
        $employer = Auth::guard('employer')->user();
        
        return view('employer.company.profile', compact('employer'));
    }

    public function updateProfile(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        
        // Company profile update logic will be implemented later
        
        return redirect()->route('employer.company.profile')->with('success', 'Company profile updated successfully!');
    }
}