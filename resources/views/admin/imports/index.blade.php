<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" x-data="{ activeTab: 'users' }">
            
            {{-- Tab Navigation --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                    <li class="mr-2">
                        <a href="#" @click.prevent="activeTab = 'users'" 
                           :class="{ 'border-blue-500 text-blue-600': activeTab === 'users', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'users' }"
                           class="inline-block p-4 border-b-2 rounded-t-lg">Import Users</a>
                    </li>
                    <li class="mr-2">
                        <a href="#" @click.prevent="activeTab = 'classes'"
                           :class="{ 'border-blue-500 text-blue-600': activeTab === 'classes', 'border-transparent hover:text-gray-600 hover:border-gray-300': activeTab !== 'classes' }"
                           class="inline-block p-4 border-b-2 rounded-t-lg">Import Classes</a>
                    </li>
                </ul>
            </div>

            {{-- Display General Messages --}}
            <x-success-message />
            <x-error-message />

            {{-- Users Import Section --}}
            <div x-show="activeTab === 'users'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Instructions for Users CSV</h3>
                    {{-- Paste the user instructions here --}}
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                        <li><strong>name</strong>, <strong>email</strong>, <strong>password</strong>, <strong>role</strong> ('student', 'teacher', or 'admin')</li>
                        <li><strong>academic_session_name</strong>, <strong>class_name</strong> (for students)</li>
                    </ul>
                    <a href="{{-- route for user template download --}}" class="text-blue-500 text-sm hover:underline mt-2 inline-block">Download User Template</a>

                    <form method="POST" action="{{ route('admin.users.import.handle') }}" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        <input type="file" name="file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600">
                        <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Import Users</button>
                    </form>
                </div>
            </div>

            {{-- Classes Import Section --}}
            <div x-show="activeTab === 'classes'" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Instructions for Classes CSV</h3>
                    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400">
                        <li><strong>name</strong>: e.g., 10A</li>
                        <li><strong>academic_session</strong>: e.g., 2023 Academic Year</li>
                        <li><strong>grading_system</strong>: e.g., Senior Secondary (Exam)</li>
                        <li><strong>subjects</strong>: e.g., English|Mathematics|History</li>
                    </ul>
                    <a href="{{ route('admin.downloads.classes-template') }}" class="text-blue-500 text-sm hover:underline mt-2 inline-block">Download Class Template</a>

                    <form method="POST" action="{{ route('admin.classes.import.handle') }}" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        <input type="file" name="file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none dark:border-gray-600">
                        <button type="submit" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Import Classes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>