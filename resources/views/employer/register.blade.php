<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employer Registration - Job4Youth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-linear-to-br from-blue-50 to-indigo-50 min-h-screen">
    <!-- Header -->
    <nav class="bg-white shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <x-application-logo class="block h-5 w-auto" />
                        <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Employer</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('employer.login') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Already have an account?
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <i class="bi bi-building-add text-2xl text-blue-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Register Your Company</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Start hiring the best talent for your company
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="bi bi-exclamation-triangle text-red-500 mr-2"></i>
                            <div class="text-red-700 text-sm">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('employer.register.submit') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Employer Name -->
                        <div class="md:col-span-2">
                            <label for="employer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-person mr-1"></i>
                                Your Name (Employer) *
                            </label>
                            <input 
                                id="employer_name" 
                                name="employer_name" 
                                type="text" 
                                required 
                                value="{{ old('employer_name') }}"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="Enter your full name"
                            >
                        </div>

                        <!-- Company Name -->
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-building mr-1"></i>
                                Company Name *
                            </label>
                            <input 
                                id="company_name" 
                                name="company_name" 
                                type="text" 
                                required 
                                value="{{ old('company_name') }}"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="Enter your company name"
                            >
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-envelope mr-1"></i>
                                Email Address *
                            </label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                required 
                                value="{{ old('email') }}"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="company@example.com"
                            >
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phoneNo" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-telephone mr-1"></i>
                                Phone Number
                            </label>
                            <input 
                                id="phoneNo" 
                                name="phoneNo" 
                                type="tel" 
                                value="{{ old('phoneNo') }}"
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="+60 3-1234 5678"
                            >
                        </div>

                        <!-- Company Size -->
                        <div>
                            <label for="company_size" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-people mr-1"></i>
                                Company Size
                            </label>
                            <select 
                                id="company_size" 
                                name="company_size" 
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                            >
                                <option value="" class="text-gray-500">Select company size</option>
                                <option value="1-10" {{ old('company_size') == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                <option value="11-50" {{ old('company_size') == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                <option value="51-200" {{ old('company_size') == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                <option value="201-500" {{ old('company_size') == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                <option value="500+" {{ old('company_size') == '500+' ? 'selected' : '' }}>500+ employees</option>
                            </select>
                        </div>

                        <!-- Company Type -->
                        <div>
                            <label for="company_type" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-briefcase mr-1"></i>
                                Company Type
                            </label>
                            <select 
                                id="company_type" 
                                name="company_type" 
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                            >
                                <option value="" class="text-gray-500">Select company type</option>
                                <option value="startup" {{ old('company_type') == 'startup' ? 'selected' : '' }}>Startup</option>
                                <option value="sme" {{ old('company_type') == 'sme' ? 'selected' : '' }}>SME</option>
                                <option value="mnc" {{ old('company_type') == 'mnc' ? 'selected' : '' }}>MNC</option>
                                <option value="government" {{ old('company_type') == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="ngo" {{ old('company_type') == 'ngo' ? 'selected' : '' }}>NGO</option>
                            </select>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-lock mr-1"></i>
                                Password *
                            </label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="Enter password"
                            >
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="bi bi-lock-fill mr-1"></i>
                                Confirm Password *
                            </label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                                placeholder="Confirm password"
                            >
                        </div>
                    </div>

                    <!-- Company Description -->
                    <div>
                        <label for="company_description" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="bi bi-card-text mr-1"></i>
                            Company Description
                        </label>
                        <textarea 
                            id="company_description" 
                            name="company_description" 
                            rows="4" 
                            class="block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-xs focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
                            placeholder="Tell us about your company..."
                        >{{ old('company_description') }}</textarea>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            required 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-sm"
                        >
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-500 underline">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:text-blue-500 underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-xs text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <i class="bi bi-check-circle mr-2"></i>
                            Create Employer Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>