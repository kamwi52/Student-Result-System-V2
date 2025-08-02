@props(['title', 'value', 'icon', 'color'])

@php
    $colors = [
        'blue' => 'from-blue-500 to-blue-400',
        'green' => 'from-green-500 to-green-400',
        'orange' => 'from-orange-500 to-orange-400',
        'pink' => 'from-pink-500 to-pink-400',
    ];
    $bgColor = $colors[$color] ?? 'from-gray-500 to-gray-400';
@endphp

<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $title }}</p>
        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $value }}</p>
    </div>
    <div class="p-4 rounded-full bg-gradient-to-tr {{ $bgColor }}">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            {!! $icon !!}
        </svg>
    </div>
</div>