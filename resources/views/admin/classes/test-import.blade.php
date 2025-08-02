<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Direct POST Test for Class Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- === ADD THIS SECTION TO DISPLAY FEEDBACK === --}}
                <x-success-message />
                <x-error-message />

                @if(session('import_errors'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">The following validation errors occurred:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach(session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ============================================= --}}

                <p class="mb-4">This test bypasses file uploads. It uses a hardcoded array of data and sends it directly to the import logic.</p>
                <p class="mb-4">Clicking the button will attempt to process 3 records (1 success, 2 failures) and roll back the transaction. You should see a list of validation errors.</p>
                
                <form action="{{ route('admin.classes.handlePostTest') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700">
                        Run Hardcoded Import Test
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="ml-4 text-gray-600 hover:underline">
                        Back to Class List
                    </a>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>