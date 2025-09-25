<x-public-layout>
    <!-- SEARCH SECTION -->
    <section class="sticky top-16 z-40 w-full bg-blue-600">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                <div class="grid grid-cols-12 gap-4">
                    
                    <!-- Job Title/Keywords Search -->
                    <div class="col-span-12 md:col-span-5 relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Job title, keywords, or company"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white/60"
                        />
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Location Search -->
                    <div class="col-span-12 md:col-span-3 relative">
                        <input
                            type="text"
                            name="location"
                            value="{{ request('location') }}"
                            placeholder="City or state"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white/60"
                        />
                        <i class="bi bi-geo-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Filter Button -->
                    <div class="col-span-6 md:col-span-2">
                        <button 
                            type="button"
                            x-data
                            @click="$dispatch('open-filters')"
                            class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-100 px-4 py-3 rounded-lg font-semibold transition-colors"
                            style="color: #FFA500;"
                        >
                            <i class="bi bi-funnel"></i>
                            Filters
                        </button>
                    </div>

                    <!-- Find Jobs Button -->
                    <div class="col-span-6 md:col-span-2">
                        <button 
                            type="submit"
                            class="w-full text-white font-semibold py-3 px-6 rounded-lg transition-colors"
                            style="background-color: #FFA500;"
                            onmouseover="this.style.backgroundColor='#E6940A'"
                            onmouseout="this.style.backgroundColor='#FFA500'"
                        >
                            Find Jobs
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- FILTERS MODAL -->
    <div 
        x-data="{ showFilters: false }"
        x-on:open-filters.window="showFilters = true"
        x-show="showFilters"
        x-transition.opacity.duration.300ms
        class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div 
            class="absolute inset-0 bg-black bg-opacity-50"
            @click="showFilters = false"
        ></div>

        <!-- Filter Panel -->
        <div 
            class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl"
            x-show="showFilters"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
        >
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Filter Jobs</h2>
                <button 
                    @click="showFilters = false"
                    class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Filter Form -->
            <div class="p-6 overflow-y-auto h-full pb-40">
                <form method="GET" action="{{ route('jobs.index') }}" id="filter-form">
                    <!-- Keep existing search values -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="location" value="{{ request('location') }}">
                    
                    <!-- Job Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Job Type</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="job_type" value="" {{ !request('job_type') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Any Type</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="job_type" value="Full-Time" {{ request('job_type') == 'Full-Time' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Full Time</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="job_type" value="Part-Time" {{ request('job_type') == 'Part-Time' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Part Time</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="job_type" value="Contract" {{ request('job_type') == 'Contract' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Contract</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="job_type" value="Internship" {{ request('job_type') == 'Internship' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Internship</span>
                            </label>
                        </div>
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Specialization</label>
                        <select name="category" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Any Specialization</option>
                            <option value="Information Technology" {{ request('category') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                            <option value="Marketing" {{ request('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Finance" {{ request('category') == 'Finance' ? 'selected' : '' }}>Finance</option>
                            <option value="Healthcare" {{ request('category') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                            <option value="Education" {{ request('category') == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Sales" {{ request('category') == 'Sales' ? 'selected' : '' }}>Sales</option>
                            <option value="Engineering" {{ request('category') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        </select>
                    </div>

                    <!-- Education Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Education Level</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="experience" value="" {{ !request('experience') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Any Level</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="experience" value="SPM" {{ request('experience') == 'SPM' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">SPM</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="experience" value="Diploma" {{ request('experience') == 'Diploma' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Diploma</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="experience" value="Degree" {{ request('experience') == 'Degree' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Bachelor's Degree</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="experience" value="Master" {{ request('experience') == 'Master' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Master's Degree</span>
                            </label>
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Salary Range (RM)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input 
                                type="number" 
                                name="min_salary"
                                value="{{ request('min_salary') }}"
                                placeholder="Min" 
                                class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                            <input 
                                type="number" 
                                name="max_salary"
                                value="{{ request('max_salary') }}"
                                placeholder="Max" 
                                class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            >
                        </div>
                    </div>

                    <!-- Posted Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Posted Date</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="posted" value="" {{ !request('posted') ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Any Time</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="posted" value="1" {{ request('posted') == '1' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Past 24 hours</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="posted" value="7" {{ request('posted') == '7' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Past week</span>
                            </label>
                            <label class="flex items-center p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="posted" value="30" {{ request('posted') == '30' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Past month</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Filter Actions -->
            <div class="absolute bottom-0 left-0 right-0 p-6 bg-white border-t border-gray-200">
                <div class="flex gap-3">
                    <a 
                        href="{{ route('jobs.index') }}"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg font-medium text-center transition-colors"
                    >
                        Clear All
                    </a>
                    <button 
                        type="button"
                        @click="document.getElementById('filter-form').submit(); showFilters = false;"
                        class="flex-1 text-white px-4 py-3 rounded-lg font-medium transition-colors"
                        style="background-color: #FFA500;"
                        onmouseover="this.style.backgroundColor='#E6940A'"
                        onmouseout="this.style.backgroundColor='#FFA500'"
                    >
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH RESULTS -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        
        <!-- Results Header -->
        <div class="md:flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Job Search Results</h1>
                <p class="text-gray-600 mt-1">
                    Found {{ $jobs->total() }} jobs
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                    @if(request('location'))
                        in {{ request('location') }}
                    @endif
                </p>
            </div>
            
            <!-- Sort Options -->
            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <label class="text-sm text-gray-600">Sort by:</label>
                <select 
                    name="sort" 
                    onchange="updateSort(this.value)"
                    class="text-sm border-gray-300 rounded-lg"
                >
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Job Title A-Z</option>
                </select>
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'location', 'job_type', 'category', 'experience', 'posted', 'min_salary', 'max_salary']))
        <div class="mb-6 p-4 bg-white rounded-lg">
            <div class="flex items-center justify-between flex-wrap">
                <div class="flex flex-wrap gap-2 mb-2 md:mb-0">
                    <span class="text-sm text-gray-600">Active filters:</span>
                    
                    @if(request('search'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Search: {{ request('search') }}
                        </span>
                    @endif
                    
                    @if(request('location'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Location: {{ request('location') }}
                        </span>
                    @endif
                    
                    @if(request('job_type'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Type: {{ request('job_type') }}
                        </span>
                    @endif
                    
                    @if(request('category'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Category: {{ request('category') }}
                        </span>
                    @endif

                    @if(request('experience'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Education: {{ request('experience') }}
                        </span>
                    @endif

                    @if(request('min_salary') || request('max_salary'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Salary: RM {{ request('min_salary', '0') }} - {{ request('max_salary', '∞') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Job Cards -->
        <div class="space-y-6">
            @forelse($jobs as $job)
            <div 
                class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 cursor-pointer flex min-h-40"
                onclick="showJobModal({{ $job->id }})"
            >
                <!-- Main content wrapper -->
                <div class="flex items-start justify-between w-full">
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Company Logo -->
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 border">
                            @if($job->employer && $job->employer->logo)
                                <img src="{{ $job->employer->logo }}" alt="{{ $job->employer->company_name }}" class="w-full h-full rounded-xl object-cover">
                            @else
                                <span class="text-blue-600 font-bold text-xl">
                                    {{ $job->employer ? substr($job->employer->company_name, 0, 1) : 'C' }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Job Info -->
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $job->title }}
                            </h3>
                            <p class="text-gray-600 mt-1 font-medium">{{ $job->employer->company_name ?? 'Company' }}</p>
                            
                            <!-- Job Meta -->
                            <div class="flex flex-wrap gap-4 mt-4 text-sm">
                                @if($job->location)
                                <span class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                    <i class="bi bi-geo-alt text-blue-500"></i>
                                    {{ $job->location }}
                                </span>
                                @endif
                                
                                @if($job->job_type)
                                <span class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                    <i class="bi bi-clock text-green-500"></i>
                                    {{ $job->job_type }}
                                </span>
                                @endif
                                
                                @if($job->specialization)
                                <span class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                    <i class="bi bi-briefcase text-purple-500"></i>
                                    {{ $job->specialization }}
                                </span>
                                @endif
                                
                                <!-- Salary display -->
                                @if($job->salary_display && ($job->salary_min || $job->salary_max))
                                <span class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                    <i class="bi bi-currency-dollar text-orange-500"></i>
                                    @if($job->salary_min && $job->salary_max)
                                        RM {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                    @elseif($job->salary_min)
                                        From RM {{ number_format($job->salary_min) }}
                                    @elseif($job->salary_max)
                                        Up to RM {{ number_format($job->salary_max) }}
                                    @endif
                                </span>
                                @else
                                <span class="flex items-center gap-2 text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                    <i class="bi bi-currency-dollar text-orange-500"></i>
                                    Undisclosed
                                </span>
                                @endif
                            </div>
                            
                            <!-- Job Description Preview -->
                            @if($job->job_overview)
                            <p class="text-gray-600 mt-3 line-clamp-2 leading-relaxed">
                                {{ Str::limit($job->job_overview, 150) }}
                            </p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Posted Date and Save Button -->
                    <div class="flex flex-col justify-between items-end h-full">            
                        <!-- Save Button -->
                        <button 
                            class="text-gray-400 hover:text-red-500 transition-colors p-2"
                            onclick="event.stopPropagation(); saveJob({{ $job->id }})"
                            title="Save this job"
                        >
                            <i class="bi bi-heart text-xl"></i>
                        </button>

                        <!-- Posted Date -->
                        <span class="text-sm text-gray-400">
                            Posted {{ $job->posted_date ? $job->posted_date->diffForHumans() : $job->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <!-- No Jobs Found -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-search text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No jobs found</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Try adjusting your search criteria or browse all available jobs to find opportunities that match your skills.</p>
                <a 
                    href="{{ route('jobs.index') }}" 
                    class="inline-block text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg"
                    style="background-color: #FFA500;"
                    onmouseover="this.style.backgroundColor='#E6940A'"
                    onmouseout="this.style.backgroundColor='#FFA500'"
                >
                    View All Jobs
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($jobs->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    <!-- Job Details Modal -->
    <dialog id="job_detail_modal" class="modal modal-bottom">
        <div class="modal-box w-full max-w-full p-0 rounded-t-2xl sm:rounded-2xl max-h-[90vh] overflow-hidden">
            
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-start justify-between">
                <div class="flex items-start gap-4 flex-1">
                    <!-- Company Logo -->
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0 border">
                        <span id="modal-company-logo" class="text-blue-600 font-bold text-xl"></span>
                    </div>
                    
                    <!-- Job Info -->
                    <div class="flex-1 min-w-0">
                        <h2 id="modal-job-title" class="text-2xl font-bold text-gray-900 mb-1"></h2>
                        
                        <!-- Company and Location -->
                        <div class="flex items-center gap-3 text-gray-600 mb-3">
                            <span id="modal-company-name" class="font-medium"></span>
                        </div>
                        
                        <!-- Job Type and Salary -->
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-mediumflex items-center gap-1 text-md" id="modal-location-container">
                                <i class="bi bi-geo-alt"></i>
                                <span id="modal-location"></span>
                            </span>
                            <span id="modal-job-type" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium"></span>
                            <span id="modal-salary" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Close Button -->
                <button onclick="closeJobModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold p-2 -mr-2">&times;</button>
            </div>

            <!-- Loading State -->
            <div id="modal-loading" class="flex justify-center items-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>

            <!-- Modal Content -->
            <div id="modal-job-details" class="overflow-y-auto flex-1" style="display: none; max-height: calc(80vh - 200px);">
                <div class="p-8 divide-y divide-gray-200 grid gap-6">
                    
                    <!-- Job Overview Section -->
                    <div id="modal-overview-section">
                        <p class="text-lg font-bold text-gray-900 mb-2">Job Overview</p>
                        <p id="modal-job-overview" class="text-gray-600 leading-relaxed text-base"></p>
                    </div>

                    <!-- Responsibilities Section -->
                    <div id="modal-responsibilities-section" class="pt-6" style="display: none;">
                        <p class="text-lg font-bold text-gray-900 mb-2">Responsibilities</p>
                        <ul id="modal-responsibilities" class="space-y-3 text-gray-600 text-base">
                            <!-- Dynamic content will be populated here -->
                        </ul>
                    </div>

                    <!-- Requirements Section -->
                    <div id="modal-requirements-section" class="pt-6" style="display: none;">
                        <p class="text-lg font-bold text-gray-900 mb-2">Requirements</p>
                        <ul id="modal-requirements" class="space-y-3 text-gray-600 text-base">
                            <!-- Dynamic content will be populated here -->
                        </ul>
                    </div>

                    <!-- Skills Section -->
                    <div id="modal-skills-section" class="pt-6" style="display: none;">
                        <p class="text-lg font-bold text-gray-900 mb-2">Skills</p>
                        <div id="modal-skills" class="flex flex-wrap gap-3">
                            <!-- Dynamic skill badges will be populated here -->
                        </div>
                    </div>

                    <!-- Additional Job Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-8">
                        <!-- Education Level -->
                        <div id="modal-education-section" style="display: none;">
                            <p class="text-lg font-semibold text-gray-900 mb-2">Education Level</p>
                            <p id="modal-education" class="text-gray-600 text-base"></p>
                        </div>

                        <!-- Experience Level -->
                        <div id="modal-experience-section" style="display: none;">
                            <p class="text-lg font-semibold text-gray-900 mb-2">Experience Level</p>
                            <p id="modal-experience" class="text-gray-600 text-base"></p>
                        </div>

                        <!-- Specialization -->
                        <div id="modal-specialization-section" style="display: none;">
                            <p class="text-lg font-semibold text-gray-900 mb-2">Specialization</p>
                            <p id="modal-specialization" class="text-gray-600 text-base"></p>
                        </div>

                        <!-- Employment Type -->
                        <div id="modal-employment-section" style="display: none;">
                            <p class="text-lg font-semibold text-gray-900 mb-2">Employment Type</p>
                            <p id="modal-employment-type" class="text-gray-600 text-base"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
             <div class="flex sticky bottom-0 bg-white border-t border-gray-200 p-6 justify-between items-center">
                <div class="text-md text-gray-500">
                    Posted <span id="modal-posted-date"></span>
                </div>
                <div class="flex justify-end gap-3">
                    <button 
                        id="save-job-btn"
                        onclick="saveJob()"
                        class="px-6 py-2 border border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors"
                    >
                        Save
                    </button>
                    <button 
                        id="apply-job-btn"
                        onclick="applyForJob()"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        Apply Now
                    </button>
                </div>
             </div>
        </div>
        
        <!-- Modal Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button onclick="closeJobModal()">close</button>
        </form>
    </dialog>

    <!-- Apply Job Modal -->
    <dialog id="apply_job_modal" class="modal">
        <div class="modal-box w-11/12 max-w-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">
                    Apply Job: <span id="apply-job-title">Job Title</span>
                </h3>
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost">✕</button>
                </form>
            </div>
            
            <!-- Modal Content -->
            <div class="py-6 space-y-6">
                <!-- Choose Resume Section -->
                <div>
                    <label class="block text-lg font-semibold text-gray-900 mb-4">Choose Resume</label>
                    <div class="relative">
                        <select 
                            id="resume-select" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white"
                        >
                            <option value="">Select...</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button 
                    type="button" 
                    onclick="closeApplyModal()" 
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Cancel
                </button>
                <button 
                    type="button" 
                    onclick="submitApplication()" 
                    id="submit-application-btn"
                    class="px-8 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    Apply Now
                </button>
            </div>
        </div>
        
        <!-- Modal Backdrop -->
        <form method="dialog" class="modal-backdrop">
            <button onclick="closeApplyModal()">close</button>
        </form>
    </dialog>

    <script>
    // Initialize apply modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter for cover letter
        const textarea = document.getElementById('cover-letter-textarea');
        const counter = document.getElementById('cover-letter-count');
        
        if (textarea && counter) {
            textarea.addEventListener('input', () => {
                const length = textarea.value.length;
                counter.textContent = length;
                
                if (length > 1000) {
                    counter.classList.add('text-red-500');
                    textarea.value = textarea.value.substring(0, 1000);
                    counter.textContent = '1000';
                } else {
                    counter.classList.remove('text-red-500');
                }
            });
        }
        
        // Resume selection validation
        const resumeSelect = document.getElementById('resume-select');
        const submitBtn = document.getElementById('submit-application-btn');
        
        if (resumeSelect && submitBtn) {
            resumeSelect.addEventListener('change', () => {
                submitBtn.disabled = !resumeSelect.value;
            });
        }
    });
    </script>
</x-public-layout>
