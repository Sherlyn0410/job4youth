<x-employer-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $jobs->total() }} Vacancies</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Search Bar -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   placeholder="Search job title..." 
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                        </div>
                        
                        <!-- Filter Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                All Jobs
                                <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Jobs</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Active</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Closed</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L8.053 10.5a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if($jobs->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" x-data="{ 
                    selectedJob: {{ $jobs->first()->id }}, 
                    activeTab: 'details',
                    jobs: {{ $jobs->toJson() }}
                }">
                    <!-- Jobs List - Left Side -->
                    <div class="lg:col-span-5 space-y-4">
                        @foreach($jobs as $job)
                            <div @click="selectedJob = {{ $job->id }}" 
                                 :class="selectedJob === {{ $job->id }} ? 'ring-2 ring-blue-500 bg-blue-50' : 'bg-white hover:bg-gray-50'" 
                                 class="border border-gray-200 rounded-lg p-4 cursor-pointer transition-all duration-200">
                                <!-- Job Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 text-lg">{{ $job->title }}</h3>
                                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600">
                                            <span class="capitalize">{{ str_replace('-', ' ', $job->job_type) }}</span>
                                            <span>•</span>
                                            @if($job->salary_display && ($job->salary_min || $job->salary_max))
                                                <span>
                                                    @if($job->salary_min && $job->salary_max)
                                                        RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}
                                                    @elseif($job->salary_min)
                                                        From RM {{ number_format($job->salary_min) }}
                                                    @elseif($job->salary_max)
                                                        Up to RM {{ number_format($job->salary_max) }}
                                                    @endif
                                                </span>
                                            @else
                                                <span>Undisclosed</span>
                                            @endif
                                            <span>•</span>
                                            <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Actions Dropdown -->
                                    <div class="relative ml-2" x-data="{ open: false }">
                                        <button @click.stop="open = !open" 
                                                class="p-1 text-gray-400 hover:text-gray-600 rounded-full">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20">
                                            <a href="{{ route('employer.jobs.edit', $job->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Job</a>
                                            <form method="POST" action="{{ route('employer.jobs.destroy', $job->id) }}" class="inline w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure?')" 
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Delete Job</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status and Applications -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <!-- Status Badge -->
                                        @if($job->status === 'open')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1"></div>
                                                Active
                                            </span>
                                        @elseif($job->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1"></div>
                                                Pending
                                            </span>
                                        @elseif($job->status === 'closed')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1"></div>
                                                Closed
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Applications Count -->
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-7.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        {{ rand(50, 300) }} Applications
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Job Details - Right Side -->
                    <div class="lg:col-span-7">
                        <!-- Single job details container that updates based on selectedJob -->
                        <div class="bg-white border border-gray-200 rounded-lg">
                            <!-- Tabs -->
                            <div class="border-b border-gray-200">
                                <nav class="flex space-x-8 px-6">
                                    <button @click="activeTab = 'details'" 
                                            :class="activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                            class="py-4 px-1 border-b-2 font-medium text-sm">
                                        Job details
                                    </button>
                                    <button @click="activeTab = 'applicants'" 
                                            :class="activeTab === 'applicants' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'" 
                                            class="py-4 px-1 border-b-2 font-medium text-sm">
                                        Applicants
                                    </button>
                                </nav>
                            </div>

                            <!-- Job Details Tab -->
                            <div x-show="activeTab === 'details'" class="p-6">
                                @foreach($jobs as $job)
                                    <div x-show="selectedJob === {{ $job->id }}">
                                        <!-- Job Info Grid -->
                                        <div class="grid grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Job Posted</h4>
                                                <p class="text-sm text-gray-900">{{ $job->created_at->format('d M, Y') }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Job Type</h4>
                                                <p class="text-sm text-gray-900 capitalize">{{ str_replace('-', ' ', $job->job_type) }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Specialization</h4>
                                                <p class="text-sm text-gray-900 capitalize">{{ $job->specialization }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Education Level</h4>
                                                <p class="text-sm text-gray-900 capitalize">{{ str_replace('-', ' ', $job->education_level) }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Location</h4>
                                                <p class="text-sm text-gray-900">{{ $job->location }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Salary</h4>
                                                <p class="text-sm text-gray-900">
                                                    @if($job->salary_display && ($job->salary_min || $job->salary_max))
                                                        @if($job->salary_min && $job->salary_max)
                                                            RM {{ number_format($job->salary_min) }} - RM {{ number_format($job->salary_max) }}
                                                        @elseif($job->salary_min)
                                                            From RM {{ number_format($job->salary_min) }}
                                                        @elseif($job->salary_max)
                                                            Up to RM {{ number_format($job->salary_max) }}
                                                        @endif
                                                    @else
                                                        Undisclosed
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Job Overview -->
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Job Overview</h3>
                                            <div class="text-gray-700 leading-relaxed">
                                                {!! nl2br(e($job->job_overview)) !!}
                                            </div>
                                        </div>

                                        <!-- Responsibilities -->
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Responsibilities</h3>
                                            <div class="text-gray-700 leading-relaxed">
                                                {!! nl2br(e($job->responsibilities)) !!}
                                            </div>
                                        </div>

                                        <!-- Requirements -->
                                        <div class="mb-6">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Requirements</h3>
                                            <div class="text-gray-700 leading-relaxed">
                                                {!! nl2br(e($job->requirements)) !!}
                                            </div>
                                        </div>

                                        <!-- Skills -->
                                        @if($job->skills)
                                            <div class="mb-6">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Required Skills</h3>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach(json_decode($job->skills, true) ?? [] as $skill)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            {{ $skill }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Applicants Tab -->
                            <div x-show="activeTab === 'applicants'" class="p-6">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-7.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No applicants yet</h3>
                                    <p class="mt-1 text-sm text-gray-500">Applications will appear here when job seekers apply to this position.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if($jobs->hasPages())
                    <div class="mt-6">
                        {{ $jobs->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg border border-gray-200 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No job posts</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by posting your first job.</p>
                    <div class="mt-6">
                        <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                            Post New Job
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-employer-layout>