<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;

class JobController extends Controller
{
    public function manage()
    {
        $employer = Auth::guard('employer')->user();
        $jobs = $employer->jobs()->latest()->paginate(10);
        
        return view('employer.jobs.manage', compact('jobs'));
    }

    public function create()
    {
        return view('employer.jobs.create');
    }

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,internship,temporary',
            'specialization' => 'required|string|max:255',
            'education_level' => 'required|in:high-school,diploma,bachelor,master,phd',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_display' => 'boolean',
            'job_overview' => 'required|string|max:1000',
            'responsibilities' => 'required|string|max:1500',
            'requirements' => 'required|string|max:1000',
            'soft_skills' => 'required|string',    // Updated from 'skills'
            'hard_skills' => 'required|string',    // New field
        ]);

        // Get the authenticated employer
        $employer = Auth::guard('employer')->user();

        // Parse skills from JSON
        $softSkills = json_decode($request->soft_skills, true) ?? [];
        $hardSkills = json_decode($request->hard_skills, true) ?? [];
        
        // Validate skills arrays
        if (empty($softSkills) || count($softSkills) > 10) {
            return back()->withErrors(['soft_skills' => 'Please select at least 1 soft skill and maximum 10 skills.'])->withInput();
        }

        if (empty($hardSkills) || count($hardSkills) > 10) {
            return back()->withErrors(['hard_skills' => 'Please select at least 1 hard skill and maximum 10 skills.'])->withInput();
        }

        // Create the job
        $job = new Job();
        $job->employer_id = $employer->id;
        $job->title = $validatedData['title'];
        $job->location = $validatedData['location'];
        $job->job_type = $validatedData['job_type'];
        $job->specialization = $validatedData['specialization'];
        $job->education_level = $validatedData['education_level'];
        $job->salary_min = $validatedData['salary_min'];
        $job->salary_max = $validatedData['salary_max'];
        $job->salary_display = $request->has('salary_display');
        $job->job_overview = $validatedData['job_overview'];
        $job->responsibilities = $validatedData['responsibilities'];
        $job->requirements = $validatedData['requirements'];
        $job->soft_skills = json_encode($softSkills);      // Updated
        $job->hard_skills = json_encode($hardSkills);      // New
        $job->status = 'pending';
        $job->posted_date = now();
        
        $job->save();

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
        return redirect()->route('employer.jobs.manage')->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        return redirect()->route('employer.jobs.manage')->with('success', 'Job deleted successfully!');
    }
}