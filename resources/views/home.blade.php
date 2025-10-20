<x-public-layout>
    <!-- Main Section -->
    <section class="relative overflow-hidden bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 py-16 md:grid-cols-2 md:items-center md:gap-16">
            <!-- Left copy -->
            <div>
                <h1 class="text-4xl font-bold leading-tight sm:text-5xl">
                Find a job that suits your skills &amp; interest.
                </h1>
                <p class="mt-4 max-w-prose text-gray-600">
                Search by job title, company, or keyword to find the right opportunity for you.
                </p>

                <!-- Search bar -->
                <form class="mt-8" action="{{ route('jobs.index') }}" method="GET" role="search" aria-label="Job search">
                    <div class="flex flex-col gap-3 rounded-xl bg-white p-2 shadow-sm ring-1 ring-gray-200 md:flex-row">
                        <label class="relative flex-1">
                        <span class="sr-only">Keywords</span>
                        <input type="text" name="search" placeholder="Job title, Keyword…" 
                            class="w-full rounded-lg border-0 pl-3 pr-3 py-3 outline-none focus:ring-2 focus:ring-blue-500" />
                        </label>

                        <label class="relative flex-1">
                        <span class="sr-only">Location</span>
                        <input type="text" name="location" placeholder="Your Location" 
                            class="w-full rounded-lg border-0 pl-3 pr-3 py-3 outline-none focus:ring-2 focus:ring-blue-500" />
                        </label>

                        <button type="submit" 
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Find Job
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right illustration (placeholder image/illustration) -->
            <div class="relative mx-auto w-full max-w-md md:max-w-none">
                <img src="{{ asset('assets/img/home-icon.png') }}" alt="Illustration" class="w-80 mx-auto" />
            </div>
        </div>
    </div>
    </section>

    <!-- Trending Job Categories -->
    <section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
        <h2 class="text-2xl font-bold">Trending Job Categories</h2>
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            View More →
        </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Example card -->
        <a href="{{ route('jobs.index', ['specialization' => 'Fresh Graduate']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Fresh Graduate Jobs</p>
            <p class="text-sm text-gray-500">357 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Internships']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Internships</p>
            <p class="text-sm text-gray-500">312 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Graduate Trainee Programs']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Graduate Trainee Programs</p>
            <p class="text-sm text-gray-500">297 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Part-Time Jobs']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Part-Time Jobs</p>
            <p class="text-sm text-gray-500">247 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Information Technology']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Information Technology</p>
            <p class="text-sm text-gray-500">357 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Sales & Marketing']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Sales &amp; Marketing</p>
            <p class="text-sm text-gray-500">312 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'Video Editing']) }}" class="group rounded-xl bg-white p-4 ring-1 ring-gray-200 transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">Video Editing</p>
            <p class="text-sm text-gray-500">297 Open position</p>
            </div>
        </a>

        <a href="{{ route('jobs.index', ['specialization' => 'HR & Recruitment Assistant']) }}" class="group rounded-xl bg-white p-4 ring-2 ring-blue-100 shadow-sm transition hover:shadow-md">
            <div>
            <p class="font-medium text-gray-900">HR &amp; Recruitment Assistant</p>
            <p class="text-sm text-gray-500">57 Open position</p>
            </div>
        </a>
        </div>
    </div>
    </section>

    <!-- Upgrade skills CTA -->
    <section class="py-16 bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 p-8 sm:p-10 text-white shadow">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
            <h3 class="text-2xl font-bold">Upgrade your skills</h3>
            <p class="mt-2 max-w-2xl text-blue-100">
            Join free or paid courses in our Skill Development Hub and grow your career.
            </p>
            </div>
            <a href="{{ route('skill-development') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 font-semibold text-blue-700 hover:bg-blue-50">
            Browse Courses →
            </a>
        </div>
        </div>
    </div>
    </section>

    <!-- Auto-open login modal if redirected from /login -->
    @if(session('show_login_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger login modal to open
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'login' }));
        });
    </script>
    @endif
</x-public-layout>
