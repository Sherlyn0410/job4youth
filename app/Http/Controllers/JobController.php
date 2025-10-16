<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use App\Models\SavedJob;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Start with base query
        $query = Job::active()->with('employer');
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('job_overview', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('skills', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('requirements', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('employer', function($eq) use ($searchTerm) {
                      $eq->where('company_name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        
        // Location filter
        if ($request->filled('location')) {
            $location = $request->input('location');
            $query->where('location', 'LIKE', "%{$location}%");
        }
        
        // Specialization filter
        if ($request->filled('specialization')) {
            $specialization = $request->input('specialization');
            $query->where('specialization', 'LIKE', "%{$specialization}%");
        }
        
        // Job type filter
        if ($request->filled('job_type')) {
            $jobType = $request->input('job_type');
            $query->where('job_type', $jobType);
        }
        
        // Education level filter
        if ($request->filled('education_level')) {
            $educationLevel = $request->input('education_level');
            $query->where('education_level', $educationLevel);
        }
        
        // Salary range filter
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->input('salary_min'));
        }
        
        if ($request->filled('salary_max')) {
            $query->where('salary_min', '<=', $request->input('salary_max'));
        }
        
        // Sorting
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'salary_high':
                $query->orderBy('salary_max', 'desc');
                break;
            case 'salary_low':
                $query->orderBy('salary_min', 'asc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        // Paginate results
        $jobs = $query->paginate(10);
        
        // Get filter options for the filter sidebar
        $specializations = Job::active()
            ->whereNotNull('specialization')
            ->select('specialization')
            ->distinct()
            ->pluck('specialization')
            ->filter()
            ->sort()
            ->values();
        
        $locations = Job::active()
            ->whereNotNull('location')
            ->select('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();
        
        $jobTypes = Job::active()
            ->whereNotNull('job_type')
            ->select('job_type')
            ->distinct()
            ->pluck('job_type')
            ->filter()
            ->sort()
            ->values();
        
        $educationLevels = Job::active()
            ->whereNotNull('education_level')
            ->select('education_level')
            ->distinct()
            ->pluck('education_level')
            ->filter()
            ->sort()
            ->values();
        
        return view('jobs', compact(
            'jobs',
            'specializations',
            'locations',
            'jobTypes',
            'educationLevels'
        ));
    }

    public function show($id)
    {
        try {
            $job = Job::active()->with('employer')->findOrFail($id);
            
            // Redirect to jobs index with the job ID to open the modal
            return redirect()->route('jobs.index', ['job' => $id]);
            
        } catch (\Exception $e) {
            return redirect()->route('jobs.index')->with('error', 'Job not found.');
        }
    }

    // API endpoint for modal data
    public function getJobDetails($id)
    {
        try {
            // Find active job by ID with employer relationship
            $job = Job::active()->with('employer')->findOrFail($id);
            
            // Increment view count
            $job->incrementViews();

            // Check if user has applied and saved (only if authenticated)
            $hasApplied = auth()->check() ? auth()->user()->hasAppliedFor($id) : false;
            $hasSaved = auth()->check() ? auth()->user()->hasSavedJob($id) : false;

            // Ensure skills are properly formatted
            $skills = $job->skills; // This will use the accessor
            
            // Return formatted job data for modal
            return response()->json([
                'success' => true,
                'job' => [
                    'id' => $job->id,
                    'title' => $job->title,
                    'company_name' => $job->employer->company_name ?? 'Company',
                    'company_logo' => $job->employer->logo ?? null,
                    'location' => $job->location,
                    'job_type' => $job->job_type,
                    'specialization' => $job->specialization,
                    'education_level' => $job->education_level,
                    'salary_min' => $job->salary_min,
                    'salary_max' => $job->salary_max,
                    'salary_display' => $job->salary_display ?? true,
                    'job_overview' => $job->job_overview,
                    'responsibilities' => $job->responsibilities,
                    'requirements' => $job->requirements,
                    'skills' => $skills,
                    'posted_date' => $job->posted_date ? $job->posted_date->diffForHumans() : $job->created_at->diffForHumans(),
                    'job_view' => $job->job_view ?? 0,
                    'has_applied' => $hasApplied,
                    'has_saved' => $hasSaved,
                    'application_count' => $job->applications()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found'
            ], 404);
        }
    }

    // Apply for job functionality
    public function applyForJob(Request $request, $id)
    {
        try {
            $job = Job::active()->with('employer')->findOrFail($id);
            $user = auth()->user();
            
            // Check if user has already applied
            if ($user->hasAppliedFor($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already applied for this job'
                ], 400);
            }
            
            // Create application
            $application = Application::create([
                'employer_id' => $job->employer_id,
                'user_id' => $user->id,
                'job_post_id' => $id,
                'status' => 'submitted',
                'apply_date' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Your application for {$job->title} at {$job->employer->company_name} has been submitted successfully!",
                'application_id' => $application->id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting application: ' . $e->getMessage()
            ], 500);
        }
    }

    // Save job functionality (if you want to implement this)
    public function saveJob($id)
    {
        try {
            $job = Job::active()->findOrFail($id);
            $user = auth()->user();
            
            // Check if job is already saved
            if ($user->hasSavedJob($id)) {
                // Unsave the job
                $user->unsaveJob($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Job removed from saved jobs',
                    'action' => 'unsaved'
                ]);
            } else {
                // Save the job
                $user->saveJob($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Job saved successfully',
                    'action' => 'saved'
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving job: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add this method to your existing JobController
    public function myApplications()
    {
        $user = auth()->user();
        
        // Get all applications by the authenticated user with related job and employer data
        // Exclude withdrawn applications
        $applications = Application::with(['job.employer'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'withdrawn') // Exclude withdrawn applications
            ->orderBy('apply_date', 'desc')
            ->paginate(10);
            
        return view('my-applications', compact('applications'));
    }

    // Add this method to your existing JobController
    public function withdrawApplication($applicationId)
    {
        try {
            $application = Application::findOrFail($applicationId);
            
            // Check if the application belongs to the authenticated user
            if ($application->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
            
            // Check if application can be withdrawn (only submitted applications)
            if ($application->status !== 'submitted') {
                return response()->json([
                    'success' => false,
                    'message' => 'This application cannot be withdrawn'
                ], 400);
            }
            
            // Update application status to withdrawn
            $application->update(['status' => 'withdrawn']);
            
            return response()->json([
                'success' => true,
                'message' => 'Application withdrawn successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error withdrawing application: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update this method in your existing JobController
    public function savedJobs()
    {
        $user = auth()->user();
        
        // Get all saved jobs by the authenticated user with related job and employer data
        $savedJobs = SavedJob::with(['job.employer'])
            ->where('user_id', $user->id)
            ->whereHas('job', function($query) {
                $query->where('status', 'open'); // Only show active jobs
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Add application status to each saved job
        $savedJobs->getCollection()->transform(function ($savedJob) use ($user) {
            $savedJob->job->has_applied = $user->hasAppliedFor($savedJob->job->id);
            $savedJob->created_at_human = $savedJob->created_at->diffForHumans();
            return $savedJob;
        });
            
        return view('saved-jobs', compact('savedJobs'));
    }
}