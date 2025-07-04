<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- SUCCESS MESSAGE ALERT -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Heads-Up Display (HUD) Section - UPDATED -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h3>
                    
                    {{-- We are now using the $stats variable from the controller --}}
                    <p class="text-gray-600 mt-2">
                        You are assigned to <strong>{{ $stats['total_classes'] }}</strong> classes with a total of <strong>{{ $stats['total_students'] }}</strong> students this session.
                    </p>
                </div>
            </div>

            <!-- Class List Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Your Assigned Classes</h3>
                    <div class="space-y-4">
                        
                        {{-- IMPORTANT: The @php block has been completely removed. --}}
                        {{-- We now rely on the $classes variable provided by the DashboardController. --}}

                        @forelse ($classes as $class)
                            <div class="border rounded-lg p-4 flex flex-col md:flex-row justify-between items-start md:items-center hover:bg-gray-50 transition">
                                <div class="flex-grow mb-4 md:mb-0">
                                    <h4 class="text-lg font-bold text-indigo-700">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600">
                                        {{ $class->subject->name }} · {{ $class->academicSession->name }}
                                    </p>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <span>Students: <strong>{{ $class->students_count }}</strong></span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <a href="#" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">View Roster</a>
                                    <a href="{{ route('teacher.gradebook.edit', $class) }}" class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                                        Enter Grades
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">You do not have any classes assigned for the current academic session.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>