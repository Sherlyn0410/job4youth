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
                class="absolute inset-0 bg-black/50"
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
                                           class="rounded-sm border-gray-300 text-blue-600 focus:ring-blue-500">
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
                    <div class="flex flex-wrap gap-2 mb-3 md:mb-0">
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

            <!-- Job Details-->
            <x-job-list-section 
                :jobs="$jobs" 
                :show-status="false"
                details-type="job"
            />
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
