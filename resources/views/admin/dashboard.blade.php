<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- ========= KPI Cards ========= -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Total Students Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Students</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $studentCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Teachers Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-indigo-500/20 mr-4">
                           <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75V5a.75.75 0 0 1 .75-.75ZM12 18.25a.75.75 0 0 1 .75.75v.01c0 .414-.336.75-.75.75a.75.75 0 0 1-.75-.75v-.01a.75.75 0 0 1 .75-.75ZM7.5 12a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75Zm9 0a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75.75 0 .75-.336.75-.75v-.01a.75.75 0 0 0-.75-.75ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                               <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75ZM7.5 18.75a.75.75 0 0 0 .75.75v.01a.75.75 0 0 0 .75-.75v-.01a.75.75 0 0 0-.75-.75.75.75 0 0 0-.75.75Zm.75-12a.75.75 0 0 0-.75-.75V6a.75.75 0 0 0 .75.75v-.01a.75.75 0 0 0 .75-.75.75.75 0 0 0-.75-.75Z" />
                           </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Teachers</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $teacherCount }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Classes Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex items-center">
                        <div class="p-3 rounded-full bg-green-500/20 mr-4">
                           <svg class="w-8 h-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h6M9 11.25h6M9 15.75h6" />
                           </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Classes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $classCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========= End KPI Cards ========= -->

            <!-- ========= Charts ========= -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- This is where the chart will be rendered --}}
                    {!! $studentsPerClassChart->container() !!}

                </div>
            </div>
            <!-- ========= End Charts ========= -->

        </div>
    </div>

    {{-- This stack is crucial for the chart's JavaScript to be injected --}}
    @push('scripts')
        {!! $studentsPerClassChart->script() !!}
    @endpush

</x-app-layout>