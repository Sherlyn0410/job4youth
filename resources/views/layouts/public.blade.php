<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Add user authentication status -->
        <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">

        <title>{{ config('app.name', 'Job4Youth') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/jobs.js'])

        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Bootstrap Icons -->
         <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>
    <body class="font-sans antialiased {{ auth()->check() ? 'authenticated' : 'guest' }}">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Modals - Only show for guests -->
        @guest
            <!-- Login Modal -->
            <x-modal name="login" maxWidth="md" :show="session('show_login_modal', false)">
                <x-auth.login-form />
            </x-modal>

            <!-- Register Modal -->
            <x-modal name="register" maxWidth="md" :show="session('show_register_modal', false)">
                <x-auth.register-form />
            </x-modal>

            <!-- Forgot Password Modal -->
            <x-modal name="forgot-password" maxWidth="md" :show="session('show_forgot_password_modal', false)">
                <x-auth.forgot-password-form />
            </x-modal>
        @endguest

        <!-- Pass user data to JavaScript -->
        <script>
            window.Laravel = {
                user: @json(auth()->user()),
                csrfToken: '{{ csrf_token() }}'
            };

            // Update authentication meta tag when user logs in
            document.addEventListener('DOMContentLoaded', function() {
                // Listen for successful login
                document.addEventListener('login-success', function() {
                    // Update the authentication meta tag
                    document.querySelector('meta[name="user-authenticated"]').setAttribute('content', 'true');
                    
                    // Add authenticated class to body
                    document.body.classList.remove('guest');
                    document.body.classList.add('authenticated');
                    
                    console.log('User logged in successfully');
                });
                
                // Listen for logout
                document.addEventListener('logout', function() {
                    // Update the authentication meta tag
                    document.querySelector('meta[name="user-authenticated"]').setAttribute('content', 'false');
                    
                    // Update body classes
                    document.body.classList.remove('authenticated');
                    document.body.classList.add('guest');
                });
            });
        </script>
    </body>
</html>
