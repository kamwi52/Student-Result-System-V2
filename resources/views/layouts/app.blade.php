<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale-1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- === FIX: Added the core script for the charting library === --}}
        <script src="{{ asset('vendor/larapex-charts/apexcharts.js') }}"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-100 dark:bg-gray-900">

            {{-- The sidebar is now part of this main layout file --}}
            <!-- Main Sidebar -->
            <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
                @include('layouts.partials.sidebar') {{-- Keep sidebar content in a partial for cleanliness --}}
            </aside>

            {{-- Main Content Area --}}
            <div class="p-4 sm:ml-64">
                {{-- This includes the top bar --}}
                @include('layouts.partials.topbar')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow mb-6 sm:rounded-lg">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>

        </div>
        
        {{-- This is where the chart-specific scripts will be injected. It is correct. --}}
        @stack('scripts')
    </body>
</html>