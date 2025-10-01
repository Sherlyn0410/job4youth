<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Job4Youth') }} - Employer Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex items-center">
                        <x-application-logo class="block h-5 w-auto" />
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Employer</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i>
                        Back to Job Seeker Site
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Form Container -->
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div x-data="{ 
                        showErrors: {{ $errors->any() ? 'true' : 'false' }},
                        clearErrors() { this.showErrors = false; }
                     }">

                    <div class="p-6">
                        <!-- Title -->
                        <div class="text-left mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Employer Login</h3>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('employer.login.submit') }}" class="space-y-4" 
                              x-data="{ isSubmitting: false }" 
                              @submit="isSubmitting = true">
                            @csrf

                            <!-- Display Validation Errors -->
                            <div x-show="showErrors && {{ $errors->any() ? 'true' : 'false' }}">
                                @if ($errors->any())
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">Login Failed</h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <ul class="list-disc list-inside space-y-1">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input id="email" 
                                       name="email" 
                                       type="email" 
                                       required 
                                       autofocus 
                                       autocomplete="username"
                                       value="{{ old('email') }}"
                                       :class="showErrors ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:border-transparent text-gray-900 placeholder-gray-500"
                                       placeholder="mail@site.com">
                            </div>

                            <!-- Password -->
                            <div x-data="{ showPassword: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <input id="password" 
                                           name="password" 
                                           :type="showPassword ? 'text' : 'password'"
                                           required 
                                           autocomplete="current-password"
                                           :class="showErrors ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                                           class="w-full px-3 py-2 pr-10 border rounded-lg focus:ring-2 focus:border-transparent text-gray-900 placeholder-gray-500"
                                           placeholder="Enter your password">
                                    
                                    <button type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Forgot Password Link -->
                                <div class="text-end mt-2">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                                        Forgot your password?
                                    </a>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center">
                                <input id="remember" 
                                       name="remember" 
                                       type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" 
                                    :disabled="isSubmitting"
                                    :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                                    class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                                <span x-show="!isSubmitting">Login</span>
                                <span x-show="isSubmitting">Signing In...</span>
                            </button>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                Don't have an employer account? 
                                <a href="{{ route('employer.register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Register Now
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>