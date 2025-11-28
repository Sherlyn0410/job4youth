<x-public-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Page Header -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900">Saved Jobs</h2>
                <p class="text-gray-600 mt-2">{{ $savedJobs->total() }} {{ Str::plural('job', $savedJobs->total()) }} saved</p>
            </div>

            @if($savedJobs->count() > 0)
                <!-- Split Layout Container -->
                <x-job-list-section 
                    :jobs="$savedJobs" 
                    :show-status="false"
                    details-type="saved-job"
                />

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl border border-gray-200 text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-heart text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">No Saved Jobs Yet</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        You haven't saved any jobs yet. Start browsing jobs and save the ones that interest you to come back to them later.
                    </p>
                    <a 
                        href="{{ route('jobs.index') }}" 
                        class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-lg transition-all duration-300 hover:shadow-lg"
                        style="background-color: #006EDC;"
                        onmouseover="this.style.backgroundColor='#005BB5'"
                        onmouseout="this.style.backgroundColor='#006EDC'"
                    >
                        <i class="bi bi-search mr-2"></i>
                        Browse Jobs
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Pass data to JavaScript -->
    <script>
        window.savedJobsPageData = {
            savedJobs: @json($savedJobs->items())
        };
    </script>
</x-public-layout>
