<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('employer.dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-5 w-auto" />
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Employer</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <x-nav-link :href="route('employer.dashboard')" :active="request()->routeIs('employer.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('employer.jobs.manage')" :active="request()->routeIs('employer.jobs.manage')">
                        {{ __('Manage Jobs') }}
                    </x-nav-link>

                    <x-nav-link :href="route('employer.company.profile')" :active="request()->routeIs('employer.company.profile')">
                        {{ __('Company Profile') }}
                    </x-nav-link>
                </div>
            </div>
            <!-- Right Side Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-hidden transition ease-in-out duration-150">
                            <div class="flex items-center">
                                @if(Auth::guard('employer')->user()->company_logo)
                                    <img class="h-6 w-6 rounded-full mr-2 object-cover" 
                                         src="{{ Auth::guard('employer')->user()->logo_url }}" 
                                         alt="Company Logo">
                                @else
                                    <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-2 text-xs">
                                        {{ Auth::guard('employer')->user()->initial }}
                                    </div>
                                @endif
                                <span class="max-w-32 truncate">{{ Auth::guard('employer')->user()->display_name }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Profile Section Header -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::guard('employer')->user()->employer_name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::guard('employer')->user()->company_name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::guard('employer')->user()->email }}</p>
                        </div>
                        
                        <x-dropdown-link :href="route('employer.user.profile')">
                            {{ __('Account Settings') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('employer.company.profile')">
                            {{ __('Company Settings') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link :href="route('employer.jobs.manage')">
                            {{ __('Manage Jobs') }}
                        </x-dropdown-link>
                        
                        <div class="border-t border-gray-100"></div>
                        
                        <x-dropdown-link :href="route('home')">
                            {{ __('View Job Seeker Site') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('employer.logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('employer.logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                {{ __('Sign Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                <!-- Post a Job Button -->
                <a href="{{ route('employer.jobs.create') }}" 
                   class="border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-md text-md font-medium transition-colors flex items-center gap-2 mr-4">
                    Post a Job
                </a>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('employer.dashboard')" :active="request()->routeIs('employer.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('employer.jobs.manage')" :active="request()->routeIs('employer.jobs.*') || request()->is('employer/jobs*')">
                {{ __('Manage Jobs') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('employer.company.profile')" class="pl-8">
                {{ __('Company Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive User Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::guard('employer')->user()->employer_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::guard('employer')->user()->company_name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::guard('employer')->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('employer.user.profile')">
                    {{ __('Account Settings') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('employer.company.profile')">
                    {{ __('Company Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('employer.logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('employer.logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-600">
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>