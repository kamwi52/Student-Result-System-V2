<!doctype html>
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

    {{-- This is where the content wrapped by <x-app-layout> will be rendered --}}
    {{-- The default slot variable is $slot --}}
    {{-- The named slot for the header is $header --}}
    {{-- This structure is based on typical Breeze app-layout component --}}

    <div class="min-h-screen bg-gray-100"> {{-- Main background and height --}}
        {{-- Include the navigation here, as it's part of the standard layout --}}
        @include('layouts.navigation')

        <!-- Page Heading -->
        {{-- Check if the header slot exists and render it --}}
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }} {{-- This outputs the content from <x-slot name="header"> --}}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }} {{-- This outputs the content *inside* <x-app-layout> (the default slot) --}}
        </main>
    </div>

    {{-- The original <div id="app"> and <main class="py-4"> with @yield('content') are now
         replaced by the structure above that uses $slot and $header.
         Ensure this old structure is NOT in your file:
    --}}
    {{--
    <div id="app">
        @include('layouts.navigation')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    --}}

</body>
</html>