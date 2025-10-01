<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $employer = Auth::guard('employer')->user();
        $jobs = $employer->jobs()->latest()->paginate(10);
        
        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('employer.jobs.create');
    }

    public function store(Request $request)
    {
        // Job creation logic will be implemented later
        return redirect()->route('employer.jobs.index')->with('success', 'Job posted successfully!');
    }

    public function show($id)
    {
        $employer = Auth::guard('employer')->user();
        $job = $employer->jobs()->findOrFail($id);
        
        return view('employer.jobs.show', compact('job'));
    }

    public function edit($id)
    {
        $employer = Auth::guard('employer')->user();
        $job = $employer->jobs()->findOrFail($id);
        
        return view('employer.jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        // Job update logic will be implemented later
        return redirect()->route('employer.jobs.index')->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        // Job deletion logic will be implemented later
        return redirect()->route('employer.jobs.index')->with('success', 'Job deleted successfully!');
    }
}