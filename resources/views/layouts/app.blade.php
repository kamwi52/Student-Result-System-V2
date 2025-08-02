<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-100 dark:bg-gray-900">

            {{-- This includes your sidebar and top navigation bar --}}
            @include('layouts.partials.sidebar') {{-- Assuming your sidebar is in a partial --}}

            {{-- Main Content Area --}}
            <div class="p-4 sm:ml-64 pt-20">

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
        
        {{-- === THIS IS THE FIX === --}}
        {{-- This line is crucial. It tells Laravel where to render any --}}
        {{-- JavaScript that is "pushed" from a child page. --}}
        @stack('scripts')
    </body>
</html>