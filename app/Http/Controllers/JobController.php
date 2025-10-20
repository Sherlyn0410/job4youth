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
                  ->orWhere('soft_skills', 'LIKE', "%{$searchTerm}%")     
                  ->orWhere('hard_skills', 'LIKE', "%{$searchTerm}%")     
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
            $job = Job::active()->with('employer')->findOrFail($id);
            $job->incrementViews();

            $hasApplied = false;
            $hasSaved = false;
            
            if (auth()->check()) {
                $hasApplied = Application::where('user_id', auth()->id())
                    ->where('job_post_id', $id)
                    ->where('status', '!=', 'withdrawn')
                    ->exists();
                
                $hasSaved = SavedJob::where('user_id', auth()->id())
                    ->where('job_post_id', $id)
                    ->exists();
            }

            $softSkills = $this->parseSkills($job->soft_skills);
            $hardSkills = $this->parseSkills($job->hard_skills);

            return response()->json([
                'success' => true,
                'job' => [
                    'id' => $job->id,
                    'title' => $job->title,
                    'company_name' => $job->employer ? $job->employer->company_name : 'Unknown Company',
                    'location' => $job->location,
                    'job_type' => $job->job_type,
                    'specialization' => $job->specialization,
                    'education_level' => $job->education_level,
                    'salary_min' => $job->salary_min,
                    'salary_max' => $job->salary_max,
                    'salary_display' => $job->salary_display,
                    'job_overview' => $job->job_overview,
                    'responsibilities' => $job->responsibilities,
                    'requirements' => $job->requirements,
                    'soft_skills' => $softSkills,
                    'hard_skills' => $hardSkills,
                    'posted_date' => $job->posted_date ? $job->posted_date->diffForHumans() : $job->created_at->diffForHumans(),
                    'has_applied' => $hasApplied,
                    'has_saved' => $hasSaved
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to parse skills that might be in different formats
     */
    private function parseSkills($skills)
    {
        if (empty($skills)) {
            return [];
        }
        
        // If it's already an array (from Laravel casting), return it
        if (is_array($skills)) {
            return array_values(array_filter($skills));
        }
        
        // If it's a string, try to decode it
        if (is_string($skills)) {
            // First try to decode as JSON
            $decoded = json_decode($skills, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                // Successfully decoded
                if (is_array($decoded)) {
                    return array_values(array_filter($decoded));
                }
                
                // If decoded result is a string, it might be double-encoded
                if (is_string($decoded)) {
                    $doubleDecoded = json_decode($decoded, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($doubleDecoded)) {
                        return array_values(array_filter($doubleDecoded));
                    }
                }
            }
            
            // If JSON decode fails, treat as comma-separated string
            return array_values(array_filter(array_map('trim', explode(',', $skills))));
        }
        
        return [];
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
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to save jobs'
                ], 401);
            }

            $job = Job::active()->findOrFail($id);
            
            // Check if job is already saved
            $existingSavedJob = SavedJob::where('user_id', auth()->id())
                ->where('job_post_id', $id)
                ->first();

            if ($existingSavedJob) {
                // Remove from saved jobs
                $existingSavedJob->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Job removed from saved jobs',
                    'action' => 'removed'
                ]);
            } else {
                // Add to saved jobs
                SavedJob::create([
                    'user_id' => auth()->id(),
                    'job_post_id' => $id
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Job saved successfully',
                    'action' => 'saved'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving job'
            ], 500);
        }
    }

    // Add this method to your existing JobController
    public function myApplications()
    {
        $user = auth()->user();
        
        $applications = Application::with(['job.employer'])
            ->where('user_id', $user->id)
            ->where('status', '!=', 'withdrawn')
            ->orderBy('apply_date', 'desc')
            ->paginate(10);
        
        $applications->getCollection()->transform(function ($application) {
            $application->apply_date_human = $application->apply_date ? $application->apply_date->diffForHumans() : '';
            return $application;
        });
            
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
        
        $savedJobs = SavedJob::with(['job.employer'])
            ->where('user_id', $user->id)
            ->whereHas('job', function($query) {
                $query->where('status', 'open');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $savedJobs->getCollection()->transform(function ($savedJob) use ($user) {
            $savedJob->job->has_applied = $user->hasAppliedFor($savedJob->job->id);
            $savedJob->created_at_human = $savedJob->created_at->diffForHumans();
            return $savedJob;
        });
        
        return view('saved-jobs', compact('savedJobs'));
    }
}