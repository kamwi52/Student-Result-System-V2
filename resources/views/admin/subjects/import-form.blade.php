<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Subjects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Import Subjects from CSV</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Upload a CSV file with subject data. The file must have columns in this exact order: <strong>name,code,description</strong>.
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('import_errors'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p class="font-bold">Please fix these errors in your file:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach(session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.subjects.import.handle') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-center">
                        <input type="file" name="subjects_file" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <button type="submit" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Import
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>