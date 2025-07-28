<x-app-flowbite-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Welcome Message --}}
            <div class="p-6 mb-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 shadow-md sm:rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Here are the classes and subjects you are assigned to teach.</p>
            </div>

            {{-- Teacher's Assigned Classes --}}
            <div class="relative overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                    <h4 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">My Assignments</h4>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($assignedClasses as $class)
                            <div class="p-4">
                                <h5 class="font-bold text-lg text-blue-600 dark:text-blue-400">{{ $class->name }}</h5>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Academic Session: {{ $class->academicSession->name }}</p>

                                {{-- List subjects for this class --}}
                                <div class="space-y-2">
                                    @foreach($class->subjects->where('pivot.teacher_id', Auth::id()) as $subject)
                                        <a href="{{ route('teacher.gradebook.assessments', ['classSection' => $class->id, 'subject' => $subject->id]) }}"
                                           class="flex justify-between items-center p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                                            <div>
                                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $subject->name }}</span>
                                            </div>
                                            <div class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                View Gradebook →
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                                You have not been assigned to any classes or subjects yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-flowbite-layout>