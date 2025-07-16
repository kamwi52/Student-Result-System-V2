<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mb-6 text-gray-600 dark:text-gray-400">Select a class below to view your grades and results.</p>
                    
                    <h4 class="text-xl font-semibold mb-2">My Classes</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($enrollments as $enrollment)
                            @php $class = $enrollment->classSection; @endphp
                            <a href="{{ route('student.class.results', $class->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                <div class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $class->name }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Teacher: {{ $class->teacher->name ?? 'N/A' }} | 
                                    Subjects: {{ $class->subjects->pluck('name')->join(', ') ?: 'N/A' }}
                                </div>
                            </a>
                        @empty
                            <p class="p-4">You are not currently enrolled in any classes.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>