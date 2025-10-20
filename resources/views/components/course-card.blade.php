@php
$enrolled = auth()->user()
? auth()->user()->courses()->where('courses.id', $course->id)->exists()
: false;
@endphp

<div class="max-w-sm bg-white rounded-2xl shadow-md overflow-hidden border {{ $enrolled ? 'border-green-500' : 'border-gray-200' }}">
    <a href="{{ route('skill-development.show', $course->slug) }}">
        <div class="relative">
            <img src="{{ $course->image_url }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
            @if($enrolled)
            <div class="absolute top-3 right-3 bg-green-100 text-green-700 font-semibold text-sm px-3 py-1 rounded-full shadow-sm flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enrolled
            </div>
            @else
            <div class="absolute top-3 right-3 bg-white text-blue-600 font-semibold text-sm px-3 py-1 rounded-full shadow-sm">
                RM {{ number_format($course->price, 2) }}
            </div>
            @endif
        </div>

        <div class="p-4">
            <h2 class="text-lg font-semibold mb-2">{{ $course->title }}</h2>
            <p class="text-sm text-gray-600">
                <span class="font-semibold">What you’ll learn:</span>
                {{ $course->description }}
            </p>
        </div>
    </a>

    <div class="px-4 py-3 border-t bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

        <div class="flex flex-wrap items-center gap-3">
            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
                </svg>
                {{ $course->level }}
            </span>

            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                {{ $course->type }}
            </span>

            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                {{ $course->learning_hours }}
            </span>

            @if($course->perks)
            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                {{ $course->perks }}
            </span>
            @endif
        </div>


    </div>

</div>