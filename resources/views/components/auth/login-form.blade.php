<div x-data="{ 
        showErrors: {{ $errors->any() ? 'true' : 'false' }},
        clearErrors() { this.showErrors = false; }
     }"
    x-on:clear-modal-errors.window="clearErrors()">

    <!-- Header with Logo and Close Button -->
    <div class="flex items-start justify-between p-6 pb-4">
        <div class="flex flex-col">
            <x-application-logo class="block h-5 w-auto" />
        </div>
        <button @click="$dispatch('close-modal', 'login')" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
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
            <h3 class="text-2xl font-bold text-gray-900">Log In</h3>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4"
            x-data="{ isSubmitting: false }"
            @submit="isSubmitting = true">
            @csrf

            <!-- Display Validation Errors -->
            <div x-show="showErrors && {{ $errors->any() ? 'true' : 'false' }}">
                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex">
                        <div class="shrink-0">
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
                <label for="modal-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="modal-email"
                    name="email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    value="{{ old('email') }}"
                    :class="showErrors ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:border-transparent"
                    placeholder="mail@site.com">
            </div>

            <!-- Password -->
            <div x-data="{ showPassword: false }">
                <label for="modal-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <input id="modal-password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        :class="showErrors ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                        class="w-full px-3 py-2 pr-10 border rounded-lg focus:ring-2 focus:border-transparent"
                        placeholder="Enter your password">

                    <button type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    </button>
                </div>

                <!-- Forgot Password Link -->
                <div class="text-end">
                    <button type="button"
                        @click="$dispatch('close-modal', 'login'); $dispatch('open-modal', 'forgot-password')"
                        class="text-sm text-blue-600 hover:text-blue-800">
                        Forgot your password?
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="modal-remember"
                    name="remember"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded-sm">
                <label for="modal-remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                :disabled="isSubmitting"
                :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''"
                class="w-full bg-blue-600 font-semibold text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <span x-show="!isSubmitting">Login</span>
                <span x-show="isSubmitting">Signing In...</span>
            </button>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <button @click="$dispatch('close-modal', 'login'); $dispatch('open-modal', 'register')" class="text-blue-600 hover:text-blue-800 font-medium">
                    Sign up here
                </button>
            </p>
        </div>
    </div>

    @if(session('login_success') || auth()->check())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.dispatchEvent(new Event('login-success'));
        });
    </script>
    @endif

</div>