    <div class="flex items-center justify-center w-full p-4">
        <div class="flex items-center bg-white rounded-md shadow-md overflow w-full border border-gray-200 h-14">

            <!-- Search Icon + Input -->
            <div class="flex items-center grow px-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#FFA500] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    name="query"
                    placeholder="Search courses..."
                    class="w-full outline-hidden border-none focus:ring-0 text-gray-700 placeholder-gray-400"
                    value="{{ request('query') }}" />
            </div>

            <!-- Filters Dropdown -->
            <div x-data="{ open: false }" class="relative inline-block text-left h-full">
                <button
                    @click="open = !open"
                    type="button"
                    class="px-4 py-2 h-full bg-white text-gray-700  hover:bg-gray-100 flex items-center space-x-2 border-l border-gray-200 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#FFA500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 018 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    <span>Filters</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    x-cloak
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute mt-2 w-80 bg-white rounded-xl shadow-lg p-4 flex flex-col gap-3 z-50"
                    style="filter: drop-shadow(0px 0px 32px rgba(0, 0, 0, 0.2));">

                    <!-- Level Filter -->
                    <div>
                        <label class="block text-gray-600 font-medium mb-1">Level</label>
                        <select name="level" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-hidden focus:ring-1 focus:ring-blue-500">
                            <option value="">All Levels</option>
                            <option value="Beginner" {{ request('level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ request('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ request('level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <label class="block text-gray-600 font-medium mb-1">Price</label>
                        <select name="price" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-hidden focus:ring-1 focus:ring-blue-500">
                            <option value="">All Prices</option>
                            <option value="0-50" {{ request('price') == '0-50' ? 'selected' : '' }}>0 - 50 RM</option>
                            <option value="51-100" {{ request('price') == '51-100' ? 'selected' : '' }}>51 - 100 RM</option>
                            <option value="101-200" {{ request('price') == '101-200' ? 'selected' : '' }}>101 - 200 RM</option>
                        </select>
                    </div>

                    <!-- Learning Hours Filter -->
                    <div>
                        <label class="block text-gray-600 font-medium mb-1">Learning Hours</label>
                        <select name="learning_hours" class="w-full border border-gray-200 rounded-md px-3 py-2 focus:outline-hidden focus:ring-1 focus:ring-blue-500">
                            <option value="">Any</option>
                            <option value="0-5" {{ request('learning_hours') == '0-5' ? 'selected' : '' }}>0 - 5 hours</option>
                            <option value="6-10" {{ request('learning_hours') == '6-10' ? 'selected' : '' }}>6 - 10 hours</option>
                            <option value="11-20" {{ request('learning_hours') == '11-20' ? 'selected' : '' }}>11 - 20 hours</option>
                        </select>
                    </div>

                    <!-- Apply Filters Button -->
                    <div class="mt-2">
                        <button type="submit" class="w-full bg-[#FFA500] text-white rounded-md px-4 py-2 hover:bg-orange-500">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Button -->
            <button
                type="submit"
                class="px-5 py-2 h-full bg-[#FFA500] hover:bg-orange-500 text-white font-medium">
                Search
            </button>
        </div>
    </div>