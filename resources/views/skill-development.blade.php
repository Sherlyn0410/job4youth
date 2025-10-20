<x-public-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form class="w-full" action="{{ route('skill-development.search') }}" method="GET">
                <x-search-bar />
            </form>

            {{-- === COURSE TYPES & RESULTS === --}}
            <div class="mt-6 p-4 rounded-lg flex flex-col md:justify-between gap-4">

                {{-- Tabs --}}
                <div class="flex flex-wrap gap-2 border-b border-gray-200">
                    <a href="{{ route('skill-development') }}"
                        class="px-4 py-2 font-medium {{ request('type') === null ? 'text-black font-black border-b border-black' : ' text-gray-500' }}">
                        All
                    </a>

                    @foreach ($courseTypes as $type)
                    <a href="{{ route('skill-development', ['type' => $type]) }}"
                        class="px-4 py-2 font-medium {{ request('type') === $type ? 'text-black font-black border-b border-black' : ' text-gray-500' }}">
                        {{ $type }}
                    </a>
                    @endforeach
                </div>


                <div class="text-gray-600 text-sm">
                    @if($courses->count() > 0)
                    Showing {{ $courses->count() }} result{{ $courses->count() > 1 ? 's' : '' }}
                    @if(request('type')) for "{{ request('type') }}" @endif
                    @else
                    No results found
                    @endif
                </div>

            </div>


            {{-- === SECTION 3: Dynamic Courses === --}}
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-4 text-gray-900">

                    @if ($courses->isEmpty())
                    <p class="text-gray-500">
                        No results found{{ request('query') ? ' for "' . request('query') . '"' : '' }}.
                    </p>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($courses as $course)
                        <x-course-card :course="$course" />
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
</x-public-layout>