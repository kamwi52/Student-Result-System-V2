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
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="mb-6 text-gray-600 dark:text-gray-400">Select a class below to view your grades and results.</p>
                        </div>
                        <div>
                             <a href="{{ route('student.my.report') }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Download My Report Card
                            </a>
                        </div>
                    </div>
                    
                    <h4 class="text-xl font-semibold mb-2 mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">My Classes</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($enrollments as $enrollment)
                            @php $class = $enrollment->classSection; @endphp
                            @if($class)
                                <a href="{{ route('student.class.results', $class->id) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                                    <div class="font-bold text-lg text-indigo-600 dark:text-indigo-400">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{-- === THIS IS THE FIX === --}}
                                        {{-- We now use our new 'subject_teachers' helper. --}}
                                        {{-- It gets all the unique teacher names and joins them into a string. --}}
                                        Teacher(s): {{ $class->subject_teachers->pluck('name')->join(', ') ?: 'N/A' }} | 
                                        Subjects: {{ $class->subjects->pluck('name')->join(', ') ?: 'N/A' }}
                                    </div>
                                </a>
                            @endif
                        @empty
                            <p class="p-4">You are not currently enrolled in any classes.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>