<x-employer-layout>
    <div class="py-8">
        <!-- Enhanced Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Job Management</h2>
                        <p class="text-gray-600 mt-1">{{ $jobs->total() }} {{ Str::plural('vacancy', $jobs->total()) }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Enhanced Search Bar -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search job titles..." 
                                   class="pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-72 bg-gray-50 focus:bg-white transition-colors shadow-sm">
                        </div>
                        
                        <!-- Enhanced Filter Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z" />
                                </svg>
                                All Jobs
                                <svg class="ml-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-10">
                                <div class="py-2">
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-3"></span>
                                        All Jobs
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                        Active
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700">
                                        <span class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></span>
                                        Pending
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700">
                                        <span class="w-2 h-2 bg-red-400 rounded-full mr-3"></span>
                                        Closed
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @if($jobs->count() > 0)
                <div class="flex gap-4 h-screen" style="height: calc(100vh - 220px);" x-data="{ 
                    selectedJob: {{ $jobs->first()->id }}, 
                    activeTab: 'details'
                }">
                    <!-- Enhanced Jobs List - Left Side (PREVIOUS LAYOUT) -->
                    <div class="w-2/5 overflow-y-auto space-y-4 pr-4">
                        @foreach($jobs as $job)
                            <div @click="selectedJob = {{ $job->id }}" 
                                    :class="selectedJob === {{ $job->id }} ? 'border border-blue-500 shadow-lg bg-white' : 'bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 hover:border-blue-300 cursor-pointer'" 
                                    class="rounded-2xl p-6 cursor-pointer transition-all duration-300 shadow-sm hover:shadow-md group">
                                
                                <!-- Enhanced Job Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $job->title }}</h3>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                                                {{ ucwords(str_replace('-', ' ', $job->job_type)) }}
                                            </span>
                                            @if($job->salary_display && ($job->salary_min || $job->salary_max))
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 font-medium">
                                                    @if($job->salary_min && $job->salary_max)
                                                        RM {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                                    @elseif($job->salary_min)
                                                        From RM {{ number_format($job->salary_min) }}
                                                    @elseif($job->salary_max)
                                                        Up to RM {{ number_format($job->salary_max) }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center mt-3 text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    <!-- Enhanced Actions Dropdown -->
                                    <div class="relative ml-4" x-data="{ open: false }">
                                        <button @click.stop="open = !open" 
                                                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-20">
                                            <div class="py-2">
                                                <a href="{{ route('employer.jobs.edit', $job->id) }}" 
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                                    <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Job
                                                </a>
                                                <form method="POST" action="{{ route('employer.jobs.destroy', $job->id) }}" class="inline w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this job?')" 
                                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                        <svg class="w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete Job
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Status and Applications -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <!-- Enhanced Status Badge -->
                                        @if($job->status === 'open')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 ring-1 ring-green-200">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                                Active
                                            </span>
                                        @elseif($job->status === 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                Pending Review
                                            </span>
                                        @elseif($job->status === 'closed')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 ring-1 ring-red-200">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                Closed
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Enhanced Applications Count -->
                                    <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1 rounded-full">
                                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="font-medium">{{ rand(12, 156) }}</span>
                                        <span class="ml-1">applications</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Job Details - Right Side (SIMPLIFIED TABS) -->
                    <div class="w-3/5 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg">
                        <!-- Simple Tabs -->
                        <div class="bg-white border-b border-gray-200">
                            <nav class="flex space-x-8 px-6">
                                <button @click="activeTab = 'details'" 
                                        :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                        class="py-4 px-1 border-b-2 font-medium text-md">
                                    Job Details
                                </button>
                                <button @click="activeTab = 'applicants'" 
                                        :class="activeTab === 'applicants' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                        class="py-4 px-1 border-b-2 font-medium text-md">
                                    Applicants
                                </button>
                            </nav>
                        </div>

                        <!-- Content Area -->
                        <div class="h-full flex flex-col">
                            <div class="flex-1 overflow-y-auto">
                                <!-- Job Details Tab -->
                                <div x-show="activeTab === 'details'" class="p-6">
                                    @foreach($jobs as $job)
                                        <div x-show="selectedJob === {{ $job->id }}">
                                            <!-- Simplified Job Info Grid -->
                                            <div class="grid grid-cols-3 gap-6 mb-8 bg-gray-50 rounded-xl p-6">
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Posted Date</h4>
                                                    <p class="text-gray-900 font-medium">{{ $job->created_at->format('M d, Y') }}</p>
                                                    <p class="text-sm text-gray-500">{{ $job->created_at->diffForHumans() }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Job Type</h4>
                                                    <p class="text-gray-900 font-medium capitalize">{{ str_replace('-', ' ', $job->job_type) }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Location</h4>
                                                    <p class="text-gray-900 font-medium">{{ $job->location }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Specialization</h4>
                                                    <p class="text-gray-900 font-medium capitalize">{{ $job->specialization }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Education Level</h4>
                                                    <p class="text-gray-900 font-medium capitalize">{{ str_replace('-', ' ', $job->education_level) }}</p>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-600 mb-1">Salary Range</h4>
                                                    <p class="text-gray-900 font-medium">
                                                        @if($job->salary_display && ($job->salary_min || $job->salary_max))
                                                            @if($job->salary_min && $job->salary_max)
                                                                RM {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                                            @elseif($job->salary_min)
                                                                From RM {{ number_format($job->salary_min) }}
                                                            @elseif($job->salary_max)
                                                                Up to RM {{ number_format($job->salary_max) }}
                                                            @endif
                                                        @else
                                                            <span class="text-gray-500">Undisclosed</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Simplified Content Sections -->
                                            <div class="space-y-6">
                                                <!-- Job Overview -->
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Job Overview</h3>
                                                    <div class="text-gray-700 leading-relaxed bg-white rounded-lg p-4 border border-gray-200">
                                                        {!! nl2br(e($job->job_overview)) !!}
                                                    </div>
                                                </div>

                                                <!-- Responsibilities -->
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Key Responsibilities</h3>
                                                    <div class="text-gray-700 leading-relaxed bg-white rounded-lg p-4 border border-gray-200">
                                                        {!! nl2br(e($job->responsibilities)) !!}
                                                    </div>
                                                </div>

                                                <!-- Requirements -->
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Requirements</h3>
                                                    <div class="text-gray-700 leading-relaxed bg-white rounded-lg p-4 border border-gray-200">
                                                        {!! nl2br(e($job->requirements)) !!}
                                                    </div>
                                                </div>

                                                <!-- Skills -->
                                                @if($job->skills && count($job->skills) > 0)
                                                    <div>
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Required Skills</h3>
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($job->skills as $skill)
                                                                @if($skill && trim($skill))
                                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                                        {{ trim($skill) }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Applicants Tab -->
                                <div x-show="activeTab === 'applicants'" class="p-6">
                                    <div class="text-center py-16">
                                        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-200 rounded-full flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-3">No Applicants Yet</h3>
                                        <p class="text-gray-500 mb-6 max-w-md mx-auto">Applications will appear here when job seekers apply to this position.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Pagination -->
                @if($jobs->hasPages())
                    <div class="mt-8 flex justify-center">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2">
                            {{ $jobs->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Enhanced Empty State -->
                <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-briefcase text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">No Job Posts Yet</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Get started by creating your first job posting. Attract top talent and grow your team!</p>
                    <a 
                        href="{{ route('employer.jobs.create') }}" 
                        class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg"
                        style="background-color: #006EDC;"
                        onmouseover="this.style.backgroundColor='#005BB5'"
                        onmouseout="this.style.backgroundColor='#006EDC'"
                    >
                        <i class="bi bi-plus-lg mr-2"></i>
                        Create Your First Job Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-employer-layout>