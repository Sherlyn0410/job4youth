<x-public-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Learning Activity</h1>
                <p class="mt-2 text-gray-600">Track your enrolled courses and learning progress</p>
            </div>

            @if($totalCourses > 0)
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-book text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Courses</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalCourses }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Completed</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $completedCourses }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-clock text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">In Progress</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $inProgressCourses }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-graph-up text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Avg Progress</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($averageProgress, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course List -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Enrolled Courses</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-200">
                        @foreach($enrolledCourses as $course)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <!-- Course Image/Icon -->
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                                @if($course->image_url)
                                                    <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-full object-cover rounded-lg">
                                                @else
                                                    <i class="bi bi-play-circle text-gray-400 text-2xl"></i>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $course->title }}</h3>
                                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $course->description }}</p>
                                                
                                                <!-- Course Details -->
                                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                                                    <span class="flex items-center">
                                                        <i class="bi bi-clock mr-1"></i>
                                                        {{ $course->learning_hours }} hours
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="bi bi-bar-chart mr-1"></i>
                                                        {{ ucfirst($course->level) }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="bi bi-tag mr-1"></i>
                                                        {{ ucfirst($course->type) }}
                                                    </span>
                                                    <span class="flex items-center">
                                                        <i class="bi bi-calendar mr-1"></i>
                                                        Enrolled {{ $course->pivot->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Progress Section -->
                                                <div class="mb-4">
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-sm font-medium text-gray-700">Progress</span>
                                                        <span class="text-sm text-gray-500">
                                                            {{ $course->pivot->progress_percentage }}% complete
                                                        </span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                                             style="width: {{ $course->pivot->progress_percentage }}%"></div>
                                                    </div>
                                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                                        <span>{{ $course->pivot->completed_hours }}/{{ $course->learning_hours }} hours</span>
                                                        @if($course->pivot->last_accessed_at)
                                                            <span>Last accessed {{ $course->pivot->last_accessed_at->diffForHumans() }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col items-end space-y-2 ml-6">
                                        @if($course->pivot->is_completed)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Completed
                                            </span>
                                            <p class="text-xs text-gray-500">
                                                {{ $course->pivot->completed_at->format('M d, Y') }}
                                            </p>
                                        @elseif($course->pivot->progress_percentage > 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <i class="bi bi-play-circle mr-1"></i>
                                                In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                <i class="bi bi-circle mr-1"></i>
                                                Not Started
                                            </span>
                                        @endif
                                        
                                        <a href="{{ route('skill-development.show', $course->slug) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            @if($course->pivot->is_completed)
                                                <i class="bi bi-arrow-repeat mr-2"></i>
                                                Review
                                            @else
                                                <i class="bi bi-play-fill mr-2"></i>
                                                Continue
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                    <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-book text-4xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">No Courses Enrolled Yet</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">
                        Start your learning journey by enrolling in courses that match your career goals and interests.
                    </p>
                    <a 
                        href="{{ route('skill-development') }}" 
                        class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg"
                        style="background-color: #006EDC;"
                        onmouseover="this.style.backgroundColor='#005BB5'"
                        onmouseout="this.style.backgroundColor='#006EDC'"
                    >
                        <i class="bi bi-search mr-2"></i>
                        Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-public-layout>
