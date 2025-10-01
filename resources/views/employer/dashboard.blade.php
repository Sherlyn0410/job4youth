<x-employer-layout>
    <div class="min-h-full py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 h-full">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $employer->display_name }}!</h1>
                            <p class="text-gray-600">Manage your job postings and applications from your dashboard.</p>
                        </div>
                        <div class="hidden md:block">
                            <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                <i class="bi bi-plus-circle"></i>
                                Post New Job
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rest of your dashboard content remains the same -->
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Statistics cards remain the same -->
            </div>

            <!-- Recent Jobs section remains the same -->
        </div>
    </div>
</x-employer-layout>