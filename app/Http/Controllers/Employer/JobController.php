<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\Application;

class JobController extends Controller
{
    public function manage(Request $request)
    {
        $employer = Auth::guard('employer')->user();
        
        // Debug: Always log request parameters
        \Log::info('Job manage page accessed', [
            'employer_id' => $employer->id,
            'request_method' => $request->method(),
            'all_parameters' => $request->all(),
            'search_param' => $request->get('search'),
            'status_param' => $request->get('status'),
            'has_search' => $request->has('search'),
            'filled_search' => $request->filled('search')
        ]);
        
        // Get jobs for this employer 
        $query = Job::where('employer_id', $employer->id);
        
        // Store original count for debugging
        $totalJobsCount = Job::where('employer_id', $employer->id)->count();
        
        // Apply search filter
        $searchTerm = null;
        $rawSearch = $request->get('search');
        if ($rawSearch !== null) {
            $searchTerm = trim($rawSearch);
        }
        
        if (!empty($searchTerm)) {
            // Log search activity for debugging
            \Log::info('Job search performed', [
                'employer_id' => $employer->id,
                'search_term' => $searchTerm,
                'total_jobs_before_search' => $totalJobsCount,
                'request_data' => $request->all()
            ]);
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('job_overview', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('specialization', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('location', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('responsibilities', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('requirements', 'LIKE', '%' . $searchTerm . '%');
            });
            
            // Debug: Log the SQL query
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            \Log::info('Search SQL Query', [
                'sql' => $sql,
                'bindings' => $bindings
            ]);
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if (in_array($status, ['open', 'pending', 'closed'])) {
                $query->where('status', $status);
            }
        }
        
        // Default sorting by latest
        $query->latest();
        
        // Eager load applications with users
        $query->with(['applications.user']);
        
        $jobs = $query->paginate(10);
        
        // Manually append query parameters to preserve them
        $jobs->appends($request->only(['search', 'status']));
        
        // Get filter counts
        $statusCounts = [
            'all' => Job::where('employer_id', $employer->id)->count(),
            'open' => Job::where('employer_id', $employer->id)->where('status', 'open')->count(),
            'pending' => Job::where('employer_id', $employer->id)->where('status', 'pending')->count(),
            'closed' => Job::where('employer_id', $employer->id)->where('status', 'closed')->count(),
        ];
        
        // Log final results for debugging
        if ($searchTerm) {
            \Log::info('Search results', [
                'search_term' => $searchTerm,
                'results_found' => $jobs->total(),
                'current_page' => $jobs->currentPage()
            ]);
        }
        
        return view('employer.jobs.manage', compact('jobs', 'statusCounts'));
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
            'soft_skills' => 'required|string',
            'hard_skills' => 'required|string',
        ]);

        // Get the authenticated employer
        $employer = Auth::guard('employer')->user();

        // Handle skills properly - check if they're already JSON or arrays
        $softSkills = [];
        $hardSkills = [];
        
        // Process soft_skills
        if (is_string($request->soft_skills)) {
            $decoded = json_decode($request->soft_skills, true);
            $softSkills = is_array($decoded) ? $decoded : [$request->soft_skills];
        } elseif (is_array($request->soft_skills)) {
            $softSkills = $request->soft_skills;
        }
        
        // Process hard_skills
        if (is_string($request->hard_skills)) {
            $decoded = json_decode($request->hard_skills, true);
            $hardSkills = is_array($decoded) ? $decoded : [$request->hard_skills];
        } elseif (is_array($request->hard_skills)) {
            $hardSkills = $request->hard_skills;
        }
        
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
        
        // Store as proper JSON arrays (Laravel will auto-encode with array cast)
        $job->soft_skills = $softSkills;  // Let Laravel handle the encoding
        $job->hard_skills = $hardSkills;  // Let Laravel handle the encoding
        
        $job->status = 'pending';
        $job->posted_date = now();
        
        $job->save();

        return redirect()->route('employer.jobs.manage')->with('success', 'Job posted successfully!');
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
        // Fix this line - use direct query instead of relationship
        $job = Job::where('employer_id', $employer->id)->findOrFail($id);
        
        return view('employer.jobs.create', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $employer = Auth::guard('employer')->user();
        // Fix this line - use direct query instead of relationship
        $job = Job::where('employer_id', $employer->id)->findOrFail($id);
        
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
            'soft_skills' => 'required|string',
            'hard_skills' => 'required|string',
        ]);

        // Handle skills properly
        $softSkills = [];
        $hardSkills = [];
        
        // Process soft_skills
        if (is_string($request->soft_skills)) {
            $decoded = json_decode($request->soft_skills, true);
            $softSkills = is_array($decoded) ? $decoded : [$request->soft_skills];
        } elseif (is_array($request->soft_skills)) {
            $softSkills = $request->soft_skills;
        }
        
        // Process hard_skills
        if (is_string($request->hard_skills)) {
            $decoded = json_decode($request->hard_skills, true);
            $hardSkills = is_array($decoded) ? $decoded : [$request->hard_skills];
        } elseif (is_array($request->hard_skills)) {
            $hardSkills = $request->hard_skills;
        }
        
        // Validate skills arrays
        if (empty($softSkills) || count($softSkills) > 10) {
            return back()->withErrors(['soft_skills' => 'Please select at least 1 soft skill and maximum 10 skills.'])->withInput();
        }

        if (empty($hardSkills) || count($hardSkills) > 10) {
            return back()->withErrors(['hard_skills' => 'Please select at least 1 hard skill and maximum 10 skills.'])->withInput();
        }

        // Update the job
        $job->update([
            'title' => $validatedData['title'],
            'location' => $validatedData['location'],
            'job_type' => $validatedData['job_type'],
            'specialization' => $validatedData['specialization'],
            'education_level' => $validatedData['education_level'],
            'salary_min' => $validatedData['salary_min'],
            'salary_max' => $validatedData['salary_max'],
            'salary_display' => $request->has('salary_display'),
            'job_overview' => $validatedData['job_overview'],
            'responsibilities' => $validatedData['responsibilities'],
            'requirements' => $validatedData['requirements'],
            'soft_skills' => $softSkills,
            'hard_skills' => $hardSkills,
        ]);

        return redirect()->route('employer.jobs.manage')->with('success', 'Job updated successfully!');
    }

    public function destroy($id)
    {
        $employer = Auth::guard('employer')->user();
        $job = Job::where('employer_id', $employer->id)->findOrFail($id);
        
        $job->delete();
        
        return redirect()->route('employer.jobs.manage')->with('success', 'Job deleted successfully!');
    }


    /**
     * Update application status (accept/reject)
     */
    public function updateApplicationStatus(Request $request, $applicationId)
    {
        $employer = Auth::guard('employer')->user();
        
        // Validate the request
        $request->validate([
            'status' => 'required|in:accepted,rejected,shortlisted'
        ]);
        
        // Find the application and make sure it belongs to this employer
        $application = Application::whereHas('job', function($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })->findOrFail($applicationId);
        
        // Update the application status
        $application->update([
            'status' => $request->status
        ]);
        
        $statusText = match($request->status) {
            'accepted' => 'accepted',
            'rejected' => 'rejected',
            'shortlisted' => 'shortlisted',
            default => 'updated'
        };
        
        return redirect()->back()->with('success', "Application has been {$statusText} successfully!");
    }

    /**
     * Delete application permanently
     */
    public function deleteApplication($applicationId)
    {
        $employer = Auth::guard('employer')->user();
        
        // Find the application and make sure it belongs to this employer
        $application = Application::whereHas('job', function($query) use ($employer) {
            $query->where('employer_id', $employer->id);
        })->findOrFail($applicationId);
        
        // Delete the application
        $application->delete();
        
        return redirect()->back()->with('success', "Application has been deleted successfully!");
    }
}