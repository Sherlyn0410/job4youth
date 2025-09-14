<x-public-layout>
    <section class="w-full bg-[#006EDC]">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-[1fr_320px_auto] gap-3">

                <!-- keyword -->
                <label class="relative">
                    <span class="sr-only">Search by keyword</span>
                    <input
                        type="text"
                        name="keyword"
                        placeholder="e.g. Software Engineer, Marketing Assistant, Data Analyst..."
                        class="w-full rounded-xl border-0 focus:ring-2 focus:ring-white/60 px-12 py-3 text-slate-900 placeholder-slate-500/70 bg-white"
                    />
                    <!-- search icon -->
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="7" stroke-width="2"></circle>
                        <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </label>

                <!-- location -->
                <label class="relative">
                    <span class="sr-only">Location</span>
                    <input
                        type="text"
                        name="location"
                        placeholder="e.g. Kuala Lumpur, Penang, Johor Bahru..."
                        class="w-full rounded-xl border-0 focus:ring-2 focus:ring-white/60 px-12 py-3 text-slate-900 placeholder-slate-500/70 bg-white"
                    />
                    <!-- pin icon -->
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z" stroke-width="2"></path>
                        <circle cx="12" cy="11" r="2.5" stroke-width="2"></circle>
                    </svg>
                </label>

                <!-- buttons -->
                <div class="flex gap-3">
                    <!-- Filters trigger -->
                    <button
                        x-data
                        @click="$dispatch('open-filters')"
                        class="flex-1 md:flex-none md:w-36 rounded-xl bg-white text-[#0F68E1] font-medium py-3 px-4 hover:bg-white/90 transition"
                        aria-controls="filters-panel"
                        aria-expanded="false"
                    >
                        <span class="inline-flex items-center gap-2 justify-center w-full">
                            <!-- sliders icon -->
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M4 6h16M4 12h10M4 18h6" stroke-width="2" stroke-linecap="round"></path>
                                <circle cx="14" cy="6" r="2" stroke-width="2"></circle>
                                <circle cx="9" cy="12" r="2" stroke-width="2"></circle>
                                <circle cx="7" cy="18" r="2" stroke-width="2"></circle>
                            </svg>
                            Filters
                        </span>
                    </button>

                    <!-- Find Job -->
                    <button class="flex-1 md:flex-none md:w-36 rounded-xl bg-[#FFA500] text-white font-semibold py-3 px-4 hover:opacity-95 transition">
                        Find Job
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FILTERS DRAWER / MODAL -->
    <div x-data="{ open:false }"
         x-on:open-filters.window="open=true"
         x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-40"
         aria-labelledby="filters-title"
         role="dialog"
         aria-modal="true">

        <!-- backdrop -->
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

        <!-- panel -->
        <div id="filters-panel"
             class="absolute right-0 top-0 h-full w-full sm:w-[440px] bg-white p-6 overflow-y-auto card-shadow"
             x-trap.inert.noscroll="open"
             x-transition.duration.200ms>
            <div class="flex items-start justify-between mb-4">
                <h2 id="filters-title" class="text-xl font-semibold">Filters</h2>
                <button class="p-2 rounded-lg hover:bg-slate-100" @click="open=false" aria-label="Close filters">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 6l12 12M18 6l-12 12" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>

            <!-- filter groups -->
            <form class="space-y-5">
                <!-- job type -->
                <fieldset class="space-y-3">
                    <legend class="font-medium">Job Type</legend>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Full time</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Part time</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Contract</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Internship</span>
                        </label>
                    </div>
                </fieldset>

                <!-- category -->
                <div>
                    <label class="block font-medium mb-2">Category</label>
                    <select class="w-full rounded-xl border-slate-300">
                        <option value="">Select a category...</option>
                        <option>Information Technology</option>
                        <option>Graphic Design</option>
                        <option>Education & Training</option>
                        <option>Marketing & Sales</option>
                        <option>Finance & Accounting</option>
                        <option>Human Resources</option>
                        <option>Engineering</option>
                        <option>Healthcare</option>
                        <option>Customer Service</option>
                        <option>Administration</option>
                    </select>
                </div>

                <!-- salary range -->
                <div>
                    <label class="block font-medium mb-2">Salary Range (RM)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" placeholder="e.g. 2000" class="rounded-xl border-slate-300" min="0">
                        <input type="number" placeholder="e.g. 5000" class="rounded-xl border-slate-300" min="0">
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Leave blank for any salary range</p>
                </div>

                <!-- posted date -->
                <div>
                    <label class="block font-medium mb-2">Posted</label>
                    <select class="w-full rounded-xl border-slate-300">
                        <option value="">Any time</option>
                        <option value="1">Past 24 hours</option>
                        <option value="7">Past 7 days</option>
                        <option value="14">Past 14 days</option>
                        <option value="30">Past 30 days</option>
                    </select>
                </div>

                <!-- experience level -->
                <fieldset class="space-y-3">
                    <legend class="font-medium">Experience Level</legend>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Entry Level (0-1 years)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Junior (1-3 years)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Mid Level (3-5 years)</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-slate-300">
                            <span>Senior (5+ years)</span>
                        </label>
                    </div>
                </fieldset>

                <!-- actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" class="flex-1 rounded-xl border border-slate-300 py-3 font-medium hover:bg-slate-50"
                            @click="$el.closest('form').reset()">
                        Reset
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-[#0F68E1] text-white font-semibold py-3 hover:opacity-95">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QUICK SUGGESTIONS (Optional - adds more context) -->
    <div class="max-w-6xl mx-auto px-4 py-4 border-b border-slate-200">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm text-slate-600">Popular searches:</span>
            <button class="text-sm bg-slate-100 hover:bg-slate-200 rounded-full px-3 py-1 transition">Software Engineer</button>
            <button class="text-sm bg-slate-100 hover:bg-slate-200 rounded-full px-3 py-1 transition">Marketing</button>
            <button class="text-sm bg-slate-100 hover:bg-slate-200 rounded-full px-3 py-1 transition">Data Analyst</button>
            <button class="text-sm bg-slate-100 hover:bg-slate-200 rounded-full px-3 py-1 transition">Graphic Designer</button>
            <button class="text-sm bg-slate-100 hover:bg-slate-200 rounded-full px-3 py-1 transition">Customer Service</button>
        </div>
    </div>

    <!-- RESULTS HEADER -->
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
            <p class="text-slate-600"><span class="font-semibold text-slate-900">78</span> Jobs Available</p>
            <div class="flex items-center gap-3">
                <label class="text-sm text-slate-600">Sort by:</label>
                <select class="text-sm border-slate-300 rounded-lg">
                    <option>Most Recent</option>
                    <option>Most Relevant</option>
                    <option>Salary (High to Low)</option>
                    <option>Salary (Low to High)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- JOB LIST -->
    <section class="max-w-6xl mx-auto px-4 space-y-4">
        <!-- card -->
        <article class="rounded-2xl border border-slate-200 card-shadow p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <img src="https://www.google.com/favicon.ico" class="w-10 h-10 rounded" alt="Company logo">
                    <div>
                        <h3 class="text-lg font-semibold hover:text-blue-600 cursor-pointer">Technical Support Specialist</h3>
                        <p class="text-slate-500 text-sm">Google Inc.</p>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 7h16M4 12h16M4 17h16" stroke-width="2"/></svg>
                                Information Technology
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path d="M12 7v5l3 3" stroke-width="2" stroke-linecap="round"/></svg>
                                Full time
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="6" width="18" height="12" rx="2" stroke-width="2"/><path d="M3 10h18" stroke-width="2"/></svg>
                                RM 3,800 – RM 4,200
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z" stroke-width="2"/><circle cx="12" cy="11" r="2.5" stroke-width="2"/></svg>
                                Kuala Lumpur
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="text-slate-500 text-sm">3 hours ago</div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Save Job</button>
                </div>
            </div>
        </article>

        <!-- card -->
        <article class="rounded-2xl border border-slate-200 card-shadow p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded bg-slate-200 grid place-items-center text-slate-500 font-semibold">L</div>
                    <div>
                        <h3 class="text-lg font-semibold hover:text-blue-600 cursor-pointer">Junior Figma Designer</h3>
                        <p class="text-slate-500 text-sm">ELITE LIMITED</p>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2">🎨 Graphic Design</span>
                            <span class="inline-flex items-center gap-2">🕒 Full time</span>
                            <span class="inline-flex items-center gap-2">💼 RM 2,800 – RM 3,000</span>
                            <span class="inline-flex items-center gap-2">📍 Penang</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="text-slate-500 text-sm">16 days ago</div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Save Job</button>
                </div>
            </div>
        </article>

        <!-- card -->
        <article class="rounded-2xl border border-slate-200 card-shadow p-5 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded bg-slate-200 grid place-items-center text-slate-600 font-bold">INTI</div>
                    <div>
                        <h3 class="text-lg font-semibold hover:text-blue-600 cursor-pointer">Lecturer</h3>
                        <p class="text-slate-500 text-sm">INTI International College Penang</p>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2">🎓 Education & Training</span>
                            <span class="inline-flex items-center gap-2">🕒 Part time</span>
                            <span class="inline-flex items-center gap-2">💼 Undisclosed</span>
                            <span class="inline-flex items-center gap-2">📍 Bayan Lepas, Penang</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="text-slate-500 text-sm">3 days ago</div>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Save Job</button>
                </div>
            </div>
        </article>
    </section>

    <!-- PAGINATION -->
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-center gap-2 py-8">
            <button class="p-2 rounded-full border border-slate-300 hover:bg-slate-50" aria-label="Previous page">←</button>
            <button class="w-9 h-9 rounded-full bg-[#0F68E1] text-white font-semibold">1</button>
            <button class="w-9 h-9 rounded-full hover:bg-slate-100">2</button>
            <button class="w-9 h-9 rounded-full hover:bg-slate-100">3</button>
            <button class="w-9 h-9 rounded-full hover:bg-slate-100">4</button>
            <button class="p-2 rounded-full border border-slate-300 hover:bg-slate-50" aria-label="Next page">→</button>
        </div>
    </div>

</x-public-layout>
