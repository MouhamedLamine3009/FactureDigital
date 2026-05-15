<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DigiFact') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <style>
        html { color-scheme: light dark; }
    </style>
</head>

<body class="min-h-screen antialiased bg-white text-slate-900 dark:bg-[#0a0a0a] dark:text-slate-100">
    <!-- Ambient gradients -->
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -top-40 left-1/2 h-[38rem] w-[64rem] -translate-x-1/2 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute top-[16rem] right-[-12rem] h-[30rem] w-[30rem] rounded-full bg-accent-500/10 blur-3xl"></div>
        <div class="absolute bottom-[-12rem] left-[-12rem] h-[28rem] w-[28rem] rounded-full bg-success-500/10 blur-3xl"></div>
    </div>

    {{ $slot }}

    @livewireScripts

    <!-- Minimal dark mode toggle via prefers-color-scheme (no extra backend) -->
    <script>
        (function () {
            const root = document.documentElement;
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (prefersDark) root.classList.add('dark');
        })();
    </script>
</body>

</html>