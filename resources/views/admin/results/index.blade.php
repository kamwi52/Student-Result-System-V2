<x-app-flowbite-layout>
    {{-- Page Header ----}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Results') }}
        </h2>
    </x-slot>

    {{-- Main Content --}}
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Action Buttons --}}
            <div class="flex items-center justify-end mb-4">
                
                {{-- === THE FIX: Use the correct starting route name === --}}
                <a href="{{ route('admin.results.import.show_step1') }}" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                    Import Results
                </a>
                
                <a href="{{ route('admin.results.create') }}" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Create Result
                </a>
            </div>

            <x-success-message />
            <x-error-message />

            {{-- Results Table --}}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    {{-- ... table content remains the same ... --}}
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $results->links() }}
            </div>

        </div>
    </div>
</x-app-flowbite-layout>