<x-app-flowbite-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    
                    {{-- Welcome Header & Download Button --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome, {{ Auth::user()->name }}!</h3>
                            <p class="mt-1 text-gray-600 dark:text-gray-400">Select a class below to view your grades and results.</p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                             <a href="{{ route('student.my.report') }}" target="_blank"
                                class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Download My Report Card
                            </a>
                        </div>
                    </div>
                    
                    <h4 class="text-xl font-semibold mb-4 mt-4 border-t border-gray-200 dark:border-gray-700 pt-6">My Classes</h4>
                    
                    {{-- List of Classes --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($enrollments as $enrollment)
                            @php $class = $enrollment->classSection; @endphp
                            @if($class)
                                <a href="{{ route('student.class.results', $class->id) }}" class="block p-4 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                                    <div class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Teacher(s): {{ $class->subject_teachers->pluck('name')->join(', ') ?: 'N/A' }}
                                    </div>
                                     <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Subjects: {{ $class->subjects->pluck('name')->join(', ') ?: 'N/A' }}
                                    </div>
                                </a>
                            @endif
                        @empty
                             <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                You are not currently enrolled in any classes.
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-flowbite-layout>