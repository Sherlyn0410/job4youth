@props([
    'jobs',
    'showStatus' => false,
    'statusField' => null,
    'detailsType' => 'job', // 'job' or 'application' or 'saved-job'
    'additionalData' => null,
    'height' => null // Allow custom height
])

@php
    // Set default heights based on page type
    $containerHeight = $height ?? ($detailsType === 'job' ? 'calc(100vh - 280px)' : 'calc(100vh - 220px)');
@endphp

<div class="flex gap-4 h-screen" style="height: {{ $containerHeight }};">
    
    <!-- LEFT SIDE: Job Cards List -->
    <div class="w-2/5 overflow-y-auto space-y-4 pr-4">
        @forelse($jobs as $index => $item)
            @php
                if ($detailsType === 'application') {
                    $job = $item->job;
                    $dataId = $item->id;
                    $onclickFunction = 'loadApplicationDetails';
                } elseif ($detailsType === 'saved-job') {
                    $job = $item->job;
                    $dataId = $item->id;
                    $onclickFunction = 'loadSavedJobDetails';
                } else {
                    $job = $item;
                    $dataId = $job->id;
                    $onclickFunction = 'loadJobDetails';
                }
            @endphp
            
            <div 
                class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:border-blue-300 cursor-pointer {{ $index === 0 ? 'border-blue-500 shadow-lg' : '' }}"
                onclick="{{ $onclickFunction }}({{ $dataId }})"
                data-{{ $detailsType }}-id="{{ $dataId }}"
            >
                <div class="flex items-start gap-4">
                    <!-- Company Logo -->
                    <div class="w-12 h-12 bg-linear-to-br from-blue-50 to-indigo-200 rounded-lg flex items-center justify-center shrink-0 border">
                        @if($job->employer->logo)
                            <img src="{{ asset('storage/' . $job->employer->logo) }}" alt="{{ $job->employer->company_name }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <span class="text-blue-600 font-bold text-lg">
                                {{ $job->employer->company_name ? substr($job->employer->company_name, 0, 1) : 'C' }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Job Info -->
                    <div class="flex-1 min-w-0">
                        <!-- Job Title and Date Row -->
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="text-lg font-bold text-gray-900 truncate flex-1 mr-4">
                                {{ $job->title }}
                            </h3>
                            <p class="text-sm text-gray-500 shrink-0">
                                @if($detailsType === 'application')
                                    Applied {{ $item->apply_date ? $item->apply_date->diffForHumans() : '' }}
                                @elseif($detailsType === 'saved-job')
                                    Saved {{ $item->created_at ? $item->created_at->diffForHumans() : '' }}
                                @else
                                    Posted {{ $job->posted_date ? $job->posted_date->diffForHumans() : $job->created_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        
                        <p class="text-gray-600 font-medium mb-3">
                            {{ $job->employer->company_name ?? 'Company' }}
                        </p>
                        
                        <!-- Status Section (for applications) -->
                        @if($showStatus && $detailsType === 'application')
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($item->status === 'submitted') bg-blue-100 text-blue-800
                                @elseif($item->status === 'reviewed') bg-yellow-100 text-yellow-800
                                @elseif($item->status === 'interviewed') bg-purple-100 text-purple-800
                                @elseif($item->status === 'accepted') bg-green-100 text-green-800
                                @elseif($item->status === 'rejected') bg-red-100 text-red-800
                                @elseif($item->status === 'withdrawn') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        @endif
                        
                        <!-- Job Details -->
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            @if($job->location)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-sm text-sm">
                                {{ $job->location }}
                            </span>
                            @endif
                            
                            @if($job->job_type)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-sm text-sm">
                                {{ $job->job_type }}
                            </span>
                            @endif
                            
                            @if($job->salary_display && $job->salary_min && $job->salary_max)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-sm text-sm">
                                RM {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                            </span>
                            @endif
                        </div>
                        
                        <!-- Job Overview Preview -->
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ Str::limit(strip_tags($job->job_overview), 120) }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
        <!-- No Jobs Found -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                @if($detailsType === 'saved-job')
                    <i class="bi bi-heart text-4xl text-gray-400"></i>
                @elseif($detailsType === 'application')
                    <i class="bi bi-briefcase text-4xl text-gray-400"></i>
                @else
                    <i class="bi bi-search text-4xl text-gray-400"></i>
                @endif
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                @if($detailsType === 'saved-job')
                    No saved jobs found
                @elseif($detailsType === 'application')
                    No applications found
                @else
                    No jobs found
                @endif
            </h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">
                @if($detailsType === 'saved-job')
                    You haven't saved any jobs yet. Start browsing jobs and save the ones that interest you.
                @elseif($detailsType === 'application')
                    You haven't applied to any jobs yet. Start exploring opportunities and apply to jobs that match your skills.
                @else
                    Try adjusting your search criteria or browse all available jobs to find opportunities that match your skills.
                @endif
            </p>
            <a 
                href="{{ route('jobs.index') }}" 
                class="inline-block text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg"
                style="background-color: #006EDC;"
                onmouseover="this.style.backgroundColor='#005BB5'"
                onmouseout="this.style.backgroundColor='#006EDC'"
            >
                Browse Jobs
            </a>
        </div>
        @endforelse
        
        <!-- Pagination -->
        @if($jobs->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- RIGHT SIDE: Details Panel -->
    <div class="w-3/5 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg">
        
        <!-- Loading State -->
        <div id="{{ $detailsType }}-details-loading" class="flex justify-center items-center h-full">
            <div class="text-center">
                <div class="w-16 h-16 bg-linear-to-br from-blue-50 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                    @if($detailsType === 'saved-job')
                        <i class="bi bi-heart text-2xl text-gray-400"></i>
                    @else
                        <i class="bi bi-briefcase text-2xl text-gray-400"></i>
                    @endif
                </div>
                <p class="text-gray-500">
                    @if($detailsType === 'saved-job')
                        Select a saved job to view details
                    @elseif($detailsType === 'application')
                        Select an application to view details
                    @else
                        Select a job to view details
                    @endif
                </p>
            </div>
        </div>

        <!-- Details Content -->
        <div id="{{ $detailsType }}-details-content" class="h-full flex flex-col" style="display: none;">
            
            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                
                <!-- Header -->
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-start gap-4">
                        <!-- Company Logo -->
                        <div class="w-16 h-16 bg-linear-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                            <span id="details-company-logo" class="text-white font-bold text-xl"></span>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h2 id="details-job-title" class="text-2xl font-bold text-gray-900 mb-2 leading-tight"></h2>
                            <p id="details-company-name" class="text-lg text-gray-600 font-medium mb-4"></p>
                            
                            <!-- Application Status (for applications only) -->
                            @if($detailsType === 'application')
                            <div class="flex items-center gap-4 mb-4">
                                <span id="details-status" class="px-3 py-2 rounded-lg text-sm font-medium"></span>
                                <span id="details-apply-date" class="text-gray-500 text-sm"></span>
                            </div>
                            @endif
                            
                            <!-- Meta -->
                            <div class="flex flex-wrap items-center gap-3">
                                <span id="details-location" class="bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-2 border">
                                    <i class="bi bi-geo-alt text-gray-500"></i>
                                </span>
                                <span id="details-job-type" class="bg-blue-50 text-blue-700 px-3 py-2 rounded-lg text-sm font-medium border border-blue-200"></span>
                                <span id="details-salary" class="bg-green-50 text-green-700 px-3 py-2 rounded-lg text-sm font-medium border border-green-200"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Overview Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Job Overview</h3>
                    <div id="details-job-overview" class="text-gray-600 leading-relaxed"></div>
                </div>

                <!-- Responsibilities Section -->
                <div id="details-responsibilities-section" class="border-b border-gray-200 pb-6" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Key Responsibilities</h3>
                    <div id="details-responsibilities" class="text-gray-600 leading-relaxed"></div>
                </div>

                <!-- Requirements Section -->
                <div id="details-requirements-section" class="border-b border-gray-200 pb-6" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Requirements</h3>
                    <div id="details-requirements" class="text-gray-600 leading-relaxed"></div>
                </div>

                <!-- Skills Section -->
                <div id="details-skills-section" class="pb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Required Skills</h3>
                    <div id="details-skills" class="flex flex-wrap gap-3"></div>
                </div>
            </div>

            <!-- Sticky Action Buttons at Bottom -->
            <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
                @if($detailsType === 'job')
                    <!-- Job actions -->
                    <div class="flex gap-4">
                        <button 
                            id="save-job-btn"
                            onclick="saveJob()"
                            class="flex-1 px-6 py-3 text-red-700 bg-red-100 font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="bi bi-heart"></i>
                            Save Job
                        </button>
                        <button 
                            id="apply-job-btn"
                            onclick="applyForJob()"
                            class="flex-2 px-8 py-3 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg"
                            style="background-color: #006EDC; flex: 2;"
                        >
                            <i class="bi bi-send"></i>
                            Apply Now
                        </button>
                    </div>
                @elseif($detailsType === 'saved-job')
                    <!-- Saved job actions -->
                    <div class="flex gap-4">
                        <button 
                            id="unsave-job-btn"
                            onclick="unsaveJob()"
                            class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="bi bi-heart-slash"></i>
                            Remove from Saved
                        </button>
                        <button 
                            id="apply-saved-job-btn"
                            onclick="applySavedJob()"
                            class="flex-2 px-8 py-3 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg"
                            style="background-color: #006EDC; flex: 2;"
                            onmouseover="this.style.backgroundColor='#005BB5'"
                            onmouseout="this.style.backgroundColor='#006EDC'"
                        >
                            <i class="bi bi-send"></i>
                            Apply Now
                        </button>
                    </div>
                @else
                    <!-- Application actions -->
                    <button 
                        id="withdraw-application-btn"
                        onclick="withdrawApplication()"
                        class="w-full px-8 py-3 bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center gap-2"
                        style="display: none;"
                    >
                        <i class="bi bi-x-circle"></i>
                        Withdraw Application
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>