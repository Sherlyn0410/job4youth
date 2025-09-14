<div x-data="{ 
        showErrors: {{ $errors->any() ? 'true' : 'false' }},
        clearErrors() { this.showErrors = false; }
     }"
     x-on:clear-modal-errors.window="clearErrors()">

    <!-- Header with Logo and Close Button -->
    <div class="flex items-start justify-between p-6 pb-4">
        <div class="flex flex-col">
            <img src="{{ asset('/assets/img/Job4Youth.png') }}" alt="Job4Youth" class="h-5 w-auto mb-2">
        </div>
        <button @click="$dispatch('close-modal', 'forgot-password')" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <span class="sr-only">Close</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <hr class="border-gray-200">

    <div class="p-6">
        <!-- Title -->
        <div class="text-left mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Forgot Password</h3>
            <p class="text-sm text-gray-600 mt-2">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4" 
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
                                <h3 class="text-sm font-medium text-red-800">Error</h3>
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
                <label for="forgot-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="forgot-email" 
                       name="email" 
                       type="email" 
                       required 
                       autofocus 
                       autocomplete="username"
                       value="{{ old('email') }}"
                       :class="showErrors && {{ $errors->has('email') ? 'true' : 'false' }} ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:border-transparent"
                       placeholder="mail@site.com">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    :disabled="isSubmitting"
                    :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <span x-show="!isSubmitting">Email Password Reset Link</span>
                <span x-show="isSubmitting">Sending Reset Link...</span>
            </button>
        </form>

        <!-- Back to Login Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Remember your password? 
                <button @click="$dispatch('close-modal', 'forgot-password'); $dispatch('open-modal', 'login')" class="text-blue-600 hover:text-blue-800 font-medium">
                    Back to Login
                </button>
            </p>
        </div>
    </div>
</div>