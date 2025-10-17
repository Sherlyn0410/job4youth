<x-public-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-900">My Learning Activities</h1>

        @if($courses->isEmpty())
        <p class="text-gray-600">You haven’t enrolled in any skill development courses yet.</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <x-course-card :course="$course" />
            @endforeach
        </div>
        @endif
    </div>
</x-public-layout>