<!-- Register Modal -->
<div x-data="{ open: false }" 
     x-on:open-register-modal.window="open = true"
     x-on:keydown.escape.window="open = false">
    <!-- Modal Backdrop -->
    <div x-show="open" 
         @click="open = false"
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50" 
         style="display: none;">
    </div>

    <!-- Modal Content -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div @click.stop class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6">
                <!-- Header with Logo and Close Button -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex flex-col">
                        <!-- Job4Youth Logo -->
                        <img src="{{ asset('/assets/img/Job4Youth.png') }}" alt="Job4Youth" class="h-5 w-auto mb-2">
                    </div>
                    
                    <!-- Close Button -->
                    <button @click="open = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <hr class="border-gray-200 mb-4">

                <!-- Subtitle -->
                <div class="text-left mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Sign Up</h3>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Registration Failed</h3>
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

                    <!-- Name -->
                    <div>
                        <label for="modal-register-name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input id="modal-register-name" 
                               name="name" 
                               type="text" 
                               required 
                               autocomplete="name"
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="Enter your full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="modal-register-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input id="modal-register-email" 
                               name="email" 
                               type="email" 
                               required 
                               autocomplete="username"
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 focus:ring-red-500 @enderror"
                               placeholder="mail@site.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="modal-register-password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="modal-register-password" 
                                   name="password" 
                                   x-data="{ showPassword: false }"
                                   :type="showPassword ? 'text' : 'password'"
                                   required 
                                   autocomplete="new-password"
                                   minlength="8"
                                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                   title="Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one number, and one special character (@$!%*?&)"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Enter secure password">
                            
                            <!-- Toggle Password Visibility Button -->
                            <button type="button" 
                                    x-data="{ showPassword: false }"
                                    @click="showPassword = !showPassword; document.getElementById('modal-register-password').type = showPassword ? 'text' : 'password'"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <!-- Eye Icon (Show) -->
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                
                                <!-- Eye Slash Icon (Hide) -->
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="modal-password-confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input id="modal-password-confirmation" 
                                   name="password_confirmation" 
                                   x-data="{ showConfirmPassword: false }"
                                   :type="showConfirmPassword ? 'text' : 'password'"
                                   required 
                                   autocomplete="new-password"
                                   minlength="8"
                                   class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password_confirmation') border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Confirm your password">
                            
                            <!-- Toggle Password Visibility Button -->
                            <button type="button" 
                                    x-data="{ showConfirmPassword: false }"
                                    @click="showConfirmPassword = !showConfirmPassword; document.getElementById('modal-password-confirmation').type = showConfirmPassword ? 'text' : 'password'"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                <!-- Eye Icon (Show) -->
                                <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                
                                <!-- Eye Slash Icon (Hide) -->
                                <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="flex items-center">
                        <input id="modal-terms" 
                               name="terms" 
                               type="checkbox" 
                               required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('terms') border-red-500 @enderror">
                        <label for="modal-terms" class="ml-2 block text-sm text-gray-700">
                            I agree to the 
                            <a href="#" class="text-blue-600 hover:text-blue-800 underline">Terms and Conditions</a>
                            and 
                            <a href="#" class="text-blue-600 hover:text-blue-800 underline">Privacy Policy</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button -->
                    <x-primary-button class="w-full">
                        Sign Up
                    </x-primary-button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <button @click="open = false; $dispatch('open-login-modal')" class="text-blue-600 hover:text-blue-800 font-medium">
                            Login here
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
