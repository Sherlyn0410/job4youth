<x-public-layout>
    <!-- Wrap everything in Alpine.js data -->
    <div x-data="{ showFilters: false }">
        
        <!-- SEARCH SECTION -->
        <section class="sticky top-16 z-40 w-full bg-blue-600">
            <div class="max-w-7xl mx-auto px-4 py-5">
                <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                    <div class="grid grid-cols-12 gap-4">
                        
                        <!-- Job Title/Keywords Search -->
                        <div class="col-span-12 md:col-span-5 relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Job title, keywords..."
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
                                placeholder="Location..."
                                class="w-full pl-10 pr-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white/60"
                            />
                            <i class="bi bi-geo-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Filter Button -->
                        <div class="col-span-6 md:col-span-2">
                            <button 
                                type="button"
                                @click="showFilters = true"
                                class="w-full px-4 py-3 bg-white text-blue-600 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                style="color: #FFA500;"
                            >
                                <i class="bi bi-funnel mr-2"></i>Filters
                            </button>
                        </div>

                        <!-- Find Jobs Button -->
                        <div class="col-span-6 md:col-span-2">
                            <button 
                                type="submit"
                                class="w-full px-4 py-3 bg-white text-blue-600 font-medium rounded-lg transition-colors"
                                style="background-color: #FFA500; color: white;"
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
                                @foreach($jobTypes as $type)
                                <label class="flex items-center">
                                    <input type="radio" name="job_type" value="{{ $type }}" 
                                           {{ request('job_type') == $type ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ ucfirst($type) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Specialization -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Specialization</label>
                            <select name="specialization" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Specializations</option>
                                @foreach($specializations as $spec)
                                <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                                    {{ $spec }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Education Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3 mt-6">Education Level</label>
                            <select name="education_level" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Education Levels</option>
                                @foreach($educationLevels as $level)
                                <option value="{{ $level }}" {{ request('education_level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                                @endforeach
                            </select>
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
                            type="submit" 
                            form="filter-form"
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

        <!-- SPLIT LAYOUT CONTAINER -->
        <div class="max-w-7xl mx-auto px-4 py-8">
            
            <!-- Results Header -->
            <div class="md:flex items-center justify-between mb-6">
                <div>
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
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Job Title A-Z</option>
                        <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Salary High to Low</option>
                        <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Salary Low to High</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'location', 'job_type', 'specialization', 'education_level']))
            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex flex-wrap gap-2 mb-2 md:mb-0">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        
                        @if(request('search'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Search: "{{ request('search') }}"
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
                        
                        @if(request('specialization'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Specialization: {{ request('specialization') }}
                        </span>
                        @endif

                        @if(request('education_level'))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                            Education: {{ request('education_level') }}
                        </span>
                        @endif
                    </div>
                    
                    <a href="{{ route('jobs.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Clear all filters
                    </a>
                </div>
            </div>
            @endif

            <!-- SPLIT LAYOUT: Jobs List (Left) + Job Details (Right) -->
            <div class="flex gap-4 h-screen" style="height: calc(100vh - 280px);">
                
                <!-- LEFT SIDE: Job Cards List -->
                <div class="w-2/5 overflow-y-auto space-y-4 pr-4">
                    @forelse($jobs as $job)
                    <div 
                        class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:border-blue-300 cursor-pointer {{ $loop->first ? 'border-blue-500 shadow-lg' : '' }}"
                        onclick="loadJobDetails({{ $job->id }})"
                        data-job-id="{{ $job->id }}"
                    >
                        <div class="flex items-start gap-4">
                            <!-- Company Logo -->
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 border">
                                <span class="text-white font-bold text-lg">
                                    {{ $job->employer->company_name ? substr($job->employer->company_name, 0, 1) : 'C' }}
                                </span>
                            </div>
                            
                            <!-- Job Info -->
                            <div class="flex-1 min-w-0">
                                <!-- Job Title and Posted Date Row -->
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-lg font-bold text-gray-900 truncate flex-1 mr-4">
                                        {{ $job->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 flex-shrink-0">
                                        {{ $job->posted_date ? $job->posted_date->diffForHumans() : $job->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <p class="text-gray-600 font-medium mb-2">
                                    {{ $job->employer->company_name ?? 'Company' }}
                                </p>
                                
                                <!-- Job Details -->
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    @if($job->location)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                        {{ $job->location }}
                                    </span>
                                    @endif
                                    
                                    @if($job->job_type)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                        {{ $job->job_type }}
                                    </span>
                                    @endif
                                    
                                    @if($job->salary_display && $job->salary_min && $job->salary_max)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
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
                    
                    <!-- Pagination -->
                    @if($jobs->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $jobs->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>

                <!-- RIGHT SIDE: Job Details -->
                <div class="w-3/5 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg">
                    
                    <!-- Loading State -->
                    <div id="job-details-loading" class="flex justify-center items-center h-full">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                                <i class="bi bi-briefcase text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">Select a job to view details</p>
                        </div>
                    </div>

                    <!-- Job Details Content -->
                    <div id="job-details-content" class="h-full flex flex-col" style="display: none;">
                        
                        <!-- Scrollable Content Area -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-8">
                            
                            <!-- Job Header -->
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start gap-4">
                                    <!-- Company Logo -->
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                        <span id="details-company-logo" class="text-white font-bold text-xl"></span>
                                    </div>
                                    
                                    <!-- Job Info -->
                                    <div class="flex-1 min-w-0">
                                        <h2 id="details-job-title" class="text-2xl font-bold text-gray-900 mb-2 leading-tight"></h2>
                                        <p id="details-company-name" class="text-lg text-gray-600 font-medium mb-4"></p>
                                        
                                        <!-- Job Meta -->
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
                            <div class="bg-gray-50 p-6 rounded-xl">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-file-text mr-2 text-blue-600"></i>
                                    Job Overview
                                </h3>
                                <div id="details-job-overview" class="text-gray-600 leading-relaxed prose prose-sm max-w-none"></div>
                            </div>

                            <!-- Responsibilities Section -->
                            <div id="details-responsibilities-section" class="bg-white border border-gray-200 rounded-xl p-6" style="display: none;">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-list-check mr-2 text-green-600"></i>
                                    Key Responsibilities
                                </h3>
                                <div id="details-responsibilities" class="space-y-3">
                                    <!-- Will be populated with styled bullet points -->
                                </div>
                            </div>

                            <!-- Requirements Section -->
                            <div id="details-requirements-section" class="bg-white border border-gray-200 rounded-xl p-6" style="display: none;">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-clipboard-check mr-2 text-orange-600"></i>
                                    Requirements
                                </h3>
                                <div id="details-requirements" class="space-y-3">
                                    <!-- Will be populated with styled bullet points -->
                                </div>
                            </div>

                            <!-- Skills Section -->
                            <div id="details-skills-section" class="bg-white border border-gray-200 rounded-xl p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-gear mr-2 text-purple-600"></i>
                                    Required Skills
                                </h3>
                                <div id="details-skills" class="flex flex-wrap gap-3">
                                    <!-- Will be populated with enhanced skill badges -->
                                </div>
                            </div>

                            <!-- Company Benefits Section -->
                            <div id="details-benefits-section" class="bg-white border border-gray-200 rounded-xl p-6" style="display: none;">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-gift mr-2 text-green-600"></i>
                                    Benefits & Perks
                                </h3>
                                <div id="details-benefits" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <!-- Will be populated with benefits -->
                                </div>
                            </div>

                            <!-- About the Company Section -->
                            <div id="details-about-company-section" class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="bi bi-building mr-2 text-blue-600"></i>
                                    About the Company
                                </h3>
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <span id="company-logo-large" class="text-white font-bold text-lg"></span>
                                    </div>
                                    <div>
                                        <h4 id="company-name-large" class="text-lg font-bold text-gray-900"></h4>
                                        <p class="text-gray-600 text-sm">Technology Company</p>
                                    </div>
                                </div>
                                <div id="details-about-company" class="text-gray-600 leading-relaxed prose prose-sm max-w-none">
                                    <!-- Will be populated with company description -->
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Action Button at Bottom -->
                        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6">
                            <div class="flex gap-4">
                                <button 
                                    id="save-job-btn"
                                    onclick="saveJob()"
                                    class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="bi bi-heart"></i>
                                    Save Job
                                </button>
                                <button 
                                    id="apply-job-btn"
                                    onclick="applyForJob()"
                                    class="flex-2 px-8 py-3 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2 shadow-lg"
                                    style="background-color: #006EDC; flex: 2;"
                                    onmouseover="this.style.backgroundColor='#005BB5'"
                                    onmouseout="this.style.backgroundColor='#006EDC'"
                                >
                                    <i class="bi bi-send"></i>
                                    Apply Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pass data to JavaScript -->
    <script>
        window.jobsPageData = {
            firstJobId: {{ $jobs->count() > 0 ? $jobs->first()->id : 'null' }},
            loginSuccess: {{ session('login_success') ? 'true' : 'false' }}
        };

    </script>

</x-public-layout>
