<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Script - Must be in head to prevent flash -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <!-- Modern Background -->
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 dark:from-slate-900 dark:via-gray-900 dark:to-slate-800 transition-colors duration-300">
        <!-- Decorative background elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-200/30 dark:bg-primary-900/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent-200/20 dark:bg-accent-900/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative min-h-screen flex flex-col">
            @include('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm border-b border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Message Modal -->
            @if (session()->has('message'))
                <div x-data="{ show: true, message: '{{ session('message') }}' }" 
                     x-show="show" 
                     x-init="setTimeout(() => { show = false }, 3000)"
                     @click="show = false"
                     x-transition:leave="transition ease-in duration-300" 
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm cursor-pointer">
                    <div @click.stop class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all scale-100"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Green Checkmark Circle -->
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        
                        <!-- Success Message -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Succès !
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-6" x-text="message">
                            {{ session('message') }}
                        </p>
                        
                        <!-- Close Button -->
                        <button @click="show = false" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-md">
                            OK
                        </button>
                    </div>
                </div>
            @endif

            <!-- Error Modal -->
            @if (session()->has('error'))
                <div x-data="{ show: true, message: '{{ session('error') }}' }" 
                     x-show="show" 
                     x-init="setTimeout(() => { show = false }, 5000)"
                     @click="show = false"
                     x-transition:leave="transition ease-in duration-300" 
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm cursor-pointer">
                    <div @click.stop class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all scale-100"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Red Error Circle -->
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        
                        <!-- Error Message -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Erreur !
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-6" x-text="message">
                            {{ session('error') }}
                        </p>
                        
                        <!-- Close Button -->
                        <button @click="show = false" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-md">
                            OK
                        </button>
                    </div>
                </div>
            @endif

            <!-- Success Modal -->
            @if (session()->has('success'))
                <div x-data="{ show: true, message: '{{ session('success') }}' }" 
                     x-show="show" 
                     x-init="setTimeout(() => { show = false }, 3000)"
                     @click="show = false"
                     x-transition:leave="transition ease-in duration-300" 
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm cursor-pointer">
                    <div @click.stop class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all scale-100"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                        
                        <!-- Green Checkmark Circle -->
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        
                        <!-- Success Message -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            Succès !
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-300 mb-6" x-text="message">
                            {{ session('success') }}
                        </p>
                        
                        <!-- Close Button -->
                        <button @click="show = false" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-md">
                            OK
                        </button>
                    </div>
                </div>
            @endif

            <div class="flex-1">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <footer class="border-t border-gray-100 dark:border-gray-700/60 bg-white/40 dark:bg-gray-900/30 backdrop-blur-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                        &copy; {{ date('Y') }} {{ config('app.name', 'DigiFact') }}. Tous droits réservés.
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        La facturation simple, adaptée aux entreprises d'Afrique de l'Ouest.
                    </p>
                </div>
            </footer>

        </div>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
