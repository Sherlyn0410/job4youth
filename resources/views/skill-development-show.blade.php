<x-public-layout>
    <div class="max-w-5xl mx-auto py-12 px-6">
        <a href="{{ route('skill-development') }}" class="text-blue-600 hover:underline">&larr; Back to All Courses</a>

        <div class="bg-white shadow-xs rounded-lg mt-6 p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/2">
                    <img src="{{ asset($course->image_url) }}" alt="{{ $course->title }}" class="w-full rounded-lg shadow-sm">
                </div>

                <div class="md:w-1/2 space-y-4">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ $course->title }}</h1>
                    <p class="text-gray-700">{{ $course->description }}</p>

                    <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                        <div><strong>Level:</strong> {{ $course->level }}</div>
                        <div><strong>Type:</strong> {{ $course->type }}</div>
                        <div><strong>Duration:</strong> {{ $course->learning_hours }}</div>
                        <div><strong>Perks:</strong> {{ $course->perks }}</div>
                    </div>

                    <div class="mt-6">
                        <span class="text-2xl font-semibold text-blue-700">RM {{ number_format($course->price, 2) }}</span>
                    </div>

                    <div x-data>
                        @auth
                        <form action="{{ route('stripe.checkout', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                                Enroll Now
                            </button>
                        </form>
                        @else
                        <button type="button"
                            @click="$dispatch('open-modal', 'login')"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Enroll Now
                        </button>

                        <p class="text-sm text-gray-500 mt-2">
                            Please log in to enroll in this course.
                        </p>
                        @endauth
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-public-layout>